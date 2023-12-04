<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function __construct()
    {
        //* 除了index, show其他 Event的操作都需要先經驗證
        $this->middleware('auth:sanctum')->except('index', 'show', 'update');

        //* 限1分鐘𡮢試最多60次, 從RouteServiceProvider.php 中的api找到1分鐘60秒的限制
        $this->middleware('throttle:api')
            ->only('store', 'update', 'destory');

        //* 改用xxPolicy管制權限
        $this->authorizeResource(Attendee::class, 'attendee');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $attendees = $event->attendees()->latest();
        return AttendeeResource::collection(
            $attendees->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $event->attendees()->create([
            'user_id' => $request->user()->id,
        ]);
        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee)
    {
        //* 事件的所有者或參與者才能改
        //* 改用xxPolicy管制權限，下一行註解
        // $this->authorize('delete-attendee', [$event, $attendee]);

        $attendee->delete();

        return response(status: 204);
    }
}
