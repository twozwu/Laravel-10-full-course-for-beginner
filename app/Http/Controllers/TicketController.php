<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Notifications\TicketUpdatedNotification;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        // $tickets = Ticket::all(); // 取得全部資料
        // $tickets = $user->isAdmin ? Ticket::orderBy('created_at', 'desc')->get() : $user->tickets; // 只取得自己的
        $tickets = $user->isAdmin ? Ticket::latest()->get() : $user->tickets; // 只取得自己的
        // dd($tickets);
        return view('ticket.index', compact('tickets', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {

        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
        ]);

        if ($request->file('attachment')) {
            $this->storeAttachment($request, $ticket);
        }
        return redirect(route('ticket.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('ticket.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('ticket.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        // $ticket->update($request->validated()); // 此方式會更新所有欄位，會把附件洗掉
        // $ticket->update(['title' => $request->title, 'description' => $request->description]); // 只修改非附件欄位
        $ticket->update($request->except('attachment')); // 此方式不會更新附件 (上面簡化)

        // 如果改變狀態會發送通知
        if ($request->has('status')) {
            // $user = User::find($ticket->user_id);
            // $user->notify(new TicketUpdatedNotification($ticket));
            $ticket->user->notify(new TicketUpdatedNotification($ticket)); // 使用關係式
        }

        // 先刪除後存檔
        if ($request->file('attachment')) {
            Storage::disk('public')->delete($ticket->attachment);
            $this->storeAttachment($request, $ticket);
        }

        return redirect(route('ticket.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect(route('ticket.index'));
    }

    protected function storeAttachment($request, $ticket)
    {
        if ($request->file('attachment')) {
            $ext = $request->file('attachment')->extension(); // 取得副檔名
            $contents = file_get_contents($request->file('attachment'));
            $filename = Str::random(25);
            $path = "attachment/$filename.$ext";
            Storage::disk('public')->put($path, $contents);
            $ticket->update(['attachment' => $path]);
        }
    }
}
