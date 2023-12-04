<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use CanLoadRelationships;

    public function __construct()
    {
        //* 除了index, show其他 Event的操作都需要先經驗證
        $this->middleware('auth:sanctum')->except('index', 'show');
        //* 限1分鐘𡮢試最多60次, 從RouteServiceProvider.php 中的api找到1分鐘60秒的限制
        $this->middleware('throttle:api')
            ->only('store', 'destory');

        //* 改用xxPolicy管制權限
        $this->authorizeResource(Event::class, 'event');
    }

    private array $relations = ['user', 'attendees', 'attendees.user'];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $query     = Event::query();
        $query = $this->loadRelationships(Event::query());

        // 以下這段，移至App\Http\Traits\CanLoadRelationship內
        // foreach ($relations as $relation) {
        //     // 有relation的值時，要列入該relation來查詢
        //     $query->when(
        //         $this->shouldIncludeRelation($relation),
        //         fn($q) => $q->with($relation)
        //     );
        // }

        // return EventResource::collection(Event::all());
        // return EventResource::collection(Event::with('user')->paginate());
        return EventResource::collection(
            $query->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $event = Event::create([
            ...$request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time'  => 'required|date',
                'end_time'    => 'required|date|after:start_time',

            ]),
            'user_id' => $request->user()->id,
        ]);
        // return new EventResource($event);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('user', 'attendees');
        // return new EventResource($event);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //* 事件的所有者或參與者才能改
        // if (Gate::denies('update-event', $event)) {
        //     abort(403, 'You are not authorized to update this event.');
        // }
        //* 換一種更精要的寫法，和上面3行的功能相同
        //* 下一行註解掉，改中EventPolicy來控制權限
        // $this->authorize('update-event', $event);

        // return $event->update( 寫這樣會回傳 boolean 而不是更改後的結果
        $event->update(
            $request->validate([
                'name'        => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time'  => 'sometimes|date',
                'end_time'    => 'sometimes|date|after:start_time',

            ])
        );
        // return new EventResource($event);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {

        $event->delete();
        return response(status: 204);
        // return response()->json([
        //     'message' => 'Event deleted successfully!',
        // ]);
    }
}
