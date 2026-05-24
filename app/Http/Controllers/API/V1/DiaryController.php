<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetDiaryRequest;
use App\Models\Diary;
use App\Http\Resources\DiaryResource;
use App\Http\Requests\StoreDiaryRequest;
use App\Http\Requests\UpdateDiaryRequest;


class DiaryController extends Controller
{
   
   
    /**
     * Display a listing of the resource.
     */
    public function index(GetDiaryRequest $request)
    {
        $validated = $request->validated();

        $diaries = request()->user()->diaries()
            ->search($validated['search'] ?? null)
            ->favourite($validated['favourite'] ?? null)
            ->dateRange($validated['start_date'] ?? null, $validated['end_date'] ?? null)
            ->sortData($validated['sort_by'] ?? null, $validated['sort_dir'] ?? 'desc')
            ->paginate(8);

        return DiaryResource::collection($diaries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiaryRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        
        $diary = Diary::create($data);

        return new DiaryResource($diary)->response()->setStatusCode(201);
    } 

    /**
     * Display the specified resource.
     */
    public function show(Diary $diary)
    {
        $this->authorize('view', $diary);

        return new DiaryResource($diary);
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(UpdateDiaryRequest $request, Diary $diary)
    {
        $this->authorize('update', $diary);

        $data = $request->validated();

        $diary->update($data);

        return new DiaryResource($diary)->response()->setStatusCode(201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diary $diary)
    {
        $this->authorize('delete', $diary);

        $diary->delete();

        return response()->noContent();
    }
}
