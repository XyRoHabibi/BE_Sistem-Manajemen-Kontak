<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index() { return Group::with('contacts')->get(); }
    public function store(Request $request) { return Group::create($request->validate(['name'=>'required'])); }
    public function show(Group $group) { return $group->load('contacts'); }
    public function update(Request $request, Group $group) { $group->update($request->validate(['name'=>'required'])); return $group; }
    public function destroy(Group $group) { $group->delete(); return response()->json(['message'=>'Group deleted']); }
}
