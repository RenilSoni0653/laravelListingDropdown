<?php

namespace App\Http\Controllers;

use App\Country;
use App\Product;
use App\ImageUpload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Intervention\Image\Facades\Image;
use App\Domains\Test\Models\Media;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $countries = DB::table('countries')->pluck('name','id');
        return view('form')->with(compact('countries'));
    }

    public function getState(Request $request)
    {
        $states = DB::table('states')->where("country_id",$request->country_id)->pluck('name','id');
        return response()->json($states);
    }

    public function getCity(Request $request)
    {
        $cities = DB::table('cities')->where("state_id",$request->state_id)->pluck('name','id');
        return response()->json($cities);
    }

    public function getFiles(Request $request)
    {
        $Files = DB::table('product')->where("TextFile",$request->TextFile);
        return response()->json($Files);
    }

    public function show()
    {
        $product = Product::paginate(10);
        return view('home')->with(compact('product'));
    }
    
    public function store(Request $request)
    {
        $id = auth()->user()->id;
        $countryName = DB::table('countries')->where('id','=',$request->country)->pluck('name');
        $stateName = DB::table('states')->where('id','=',$request->state)->pluck('name');
        $cityName = DB::table('cities')->where('id','=',$request->city)->pluck('name'); 
        
        $Description = explode('<p>',$request->description);
        $description = explode('</p>',$Description[1]);

        $List = new Product;
        $List->name = $request->name;
        $List->description = $description[0];
        $List->country = $countryName[0];
        $List->state = $stateName[0];
        $List->city = $cityName[0];
        $List->TextFile = $request->document[0];
        $List->save();

        return redirect('/home/'.$id);
    }

    public function edit($id)
    {
        $list = Product::find($id);
        $countries = DB::table('countries')->pluck('name','id');
   
        $countryId = request()->country_id;
        $countryid = explode('[',$countryId);
        $country_record = explode(']',$countryid[1]);
        $states = DB::table('states')->select('country_id','name')->where('country_id','=',$country_record[0])->pluck('name');

        $stateId = request()->state_id;
        $stateid = explode('[',$stateId);
        $state_record = explode(']',$stateid[1]);
        $state_key = $state_record[0];

        $city_id = request()->city_id;
        $CityId = explode('[',$city_id);
        $cityId = explode(']',$CityId[1]);
        $city_key = $cityId[0];
        $cities = DB::table('cities')->select('state_id','name')->where('state_id','=',$city_key)->pluck('name');
        
        return view('edit')->with(compact('list','countries','country_record','states','state_key','cities','city_key'));
    }

    public function update($id)
    {
        $countryName = DB::table('countries')->where('id','=',request()->country)->pluck('name');
        $stateName = DB::table('states')->where('id','=',request()->state)->pluck('name');
        $cityName = DB::table('cities')->where('id','=',request()->city)->pluck('name');
        
        $Description = explode('<p>',request()->description);
        $description = explode('</p>',$Description[1]);

        $product = DB::table('products')->where('id','=',$id)->update([
            'name' => request()->name,
            'description' => $description[0],
            'country' => $countryName[0],
            'state' => $stateName[0],
            'city' => $cityName[0],
            'TextFile' => request()->document[0]
        ]);

        return redirect('/home/'.$id);
    }

    // Below 3 functions for ImageUpload
    public function storeMedia(Request $request)
    {
        $path = public_path('uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function storeImage(Product $request)
    {
        $id = auth()->user()->id;
        $project = Product::create($request->all());

        foreach ($request->input('document', []) as $file) {
            $project->addMedia(storage_path('storage/uploads/' . $file))->toMediaCollection('document');
        }

        return redirect('/home/'.$id);
    }

    public function updateImage(UpdateProjectRequest $request, Project $project)
    {
        $id = auth()->user()->id;
        $project->update($request->all());

        if (count($project->document) > 0) {
            foreach ($project->document as $media) {
                if (!in_array($media->file_name, $request->input('document', []))) {
                    $media->delete();
                }
            }
        }

        $media = $project->document->pluck('file_name')->toArray();

        foreach ($request->input('document', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $project->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document');
            }
        }

        return redirect('/home/'.$id);
    }
}
