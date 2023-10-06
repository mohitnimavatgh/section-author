<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\{User,Section};

use Auth;

class SectionController extends Controller
{
    public function index(Request $request) {
        try {
           if(isset($request->type) && $request->type == "section") {
               $sections = Section::whereNull('parent_id')->get();
               return response()->json(['status' => true, 'data' => $sections, 'message' => "Section Lists fetch Successfully"], 200);
           }
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }
    }

    public function createSection(Request $request) {
        try {
            $user = Auth::user();
            if($user->can('create-section')) {

                $rules = ['name' => 'required'];
                $validator = Validator::make($request->all() , $rules, 
                            $messages = [
                                'name.required' => 'Section Name is Required',
                            ]
                        );
                if ($validator->fails())
                {
                    return response()
                        ->json(['status' => false, 'message' => $validator->errors() ], 403);
                }
                $data = array(
                    "name" => $request->name,
                    "user_id" => Auth::id(),
                );
                $section = Section::create($data);
                return response()->json(['status' => true, 'data' => $section, 'message' => "Section Created Successfully"], 200);
            } else {
                return response()->json(['status' => true, 'message' => "You Have No permission For Create Section"], 400);
            }
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }
    }

    public function createSubSection(Request $request) {
        try {
            $user = Auth::user();
            if($user->can('create-section')) { 

                $rules = ['name' => 'required' , 'section_id' => 'required|exists:sections,id'];
                $validator = Validator::make($request->all() , $rules, 
                            $messages = [
                                'name.required' => 'Sub Section Name is Required',
                                'section_id.required' => 'Section Id is Required',
                                'section_id.exists' => 'Section Id is not exists',
                            ]
                        );
                if ($validator->fails())
                {
                    return response()
                        ->json(['status' => false, 'message' => $validator->errors() ], 403);
                }
                $data = array(
                    "parent_id" => $request->section_id,
                    "name" => $request->name,
                    "user_id" => Auth::id(),
                );
                $section = Section::create($data);
                return response()->json(['status' => true, 'data' => $section, 'message' => "Section Created Successfully"], 200);
            } else {
                return response()->json(['status' => true, 'message' => "You Have No permission For Create Section"], 400);
            }
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }
    }

    public function editSection(Request $request) {
        try {
            $user = Auth::user();
            if($user->can('edit-section')) { 

                $rules = ['name' => 'required' , 'id' => "required"];
                $validator = Validator::make($request->all() , $rules, 
                            $messages = [
                                'name.required' => 'Section Name is Required',
                                'id.required' => 'Section Id is Required',
                            ]
                        );
                if ($validator->fails())
                {
                    return response()
                        ->json(['status' => false, 'message' => $validator->errors() ], 403);
                }
                $data = array(
                    "name" => $request->name,
                );
                $section = Section::where('id',$request->id)->update($data);
                $sectionRes = Section::find($request->id);
                return response()->json(['status' => true, 'data' => $sectionRes, 'message' => "Section Update Successfully"], 200);
            } else {
                return response()->json(['status' => true, 'message' => "You Have No permission For Create Section"], 400);

            }
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }
    }

    public function editSubSection(Request $request) {
        try {
            $user = Auth::user();
            if($user->can('edit-section')) { 

                $rules = ['name' => 'required' , 'section_id' => 'required|exists:sections,id'];
                $validator = Validator::make($request->all() , $rules, 
                            $messages = [
                                'name.required' => 'Sub Section Name is Required',
                                'section_id.required' => 'Section Id is Required',
                                'section_id.exists' => 'Section Id is not exists',
                            ]
                        );
                if ($validator->fails())
                {
                    return response()
                        ->json(['status' => false, 'message' => $validator->errors() ], 403);
                }
                $data = array(
                    "parent_id" => $request->section_id,
                    "name" => $request->name,
                );
                $sectionRes = [];
                $check = Section::where('id' , $request->id)->whereNotNull('parent_id')->first();
                if($check) {
                    $section = Section::where('id',$request->id)->update($data);
                    $sectionRes = Section::find($request->id);
                } else {
                     return response()->json(['status' => false, 'message' => "Provided Section are Wrong" ], 500);
                }
                return response()->json(['status' => true, 'data' => $sectionRes, 'message' => "Section Created Successfully"], 200);
            } else {
                return response()->json(['status' => true, 'message' => "You Have No permission For Create Section"], 400);
            }
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }
    }
}
