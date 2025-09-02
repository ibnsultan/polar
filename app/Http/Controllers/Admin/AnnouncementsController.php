<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Role;

/**
 * Permissions
 * view_announcements: [none, all]
 * create_announcements: [none, all]
 * update_announcements: [none, owned, all]
 * delete_announcements: [none, owned, all]
 */

class AnnouncementsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if(!can('view_announcements')){
            abort(403, 'You do not have permission to view announcements');
        }

        $announcements = Announcement::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $roles = Role::all();

        $this->announcements = $announcements;
        $this->roles = $roles;

        return view('admin.announcements.index', $this->data);
    }

    public function store(Request $request)
    {
        try {
            if(!can('create_announcements')){
                return $this->jsonError('You do not have permission to create announcements', 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'action_url' => 'nullable|url',
                'target_roles' => 'nullable|array',
                'target_roles.*' => 'exists:roles,id',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return $this->jsonError($validator->errors()->first(), 422);
            }

            $data = $validator->validated();
            $data['created_by'] = user()->id;

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('announcements', 'public');
                $data['image'] = $imagePath;
            }

            $announcement = Announcement::create($data);

            $this->announcement = $announcement->load('creator');

            $this->redirect = route('admin.announcements.index');
            return $this->jsonSuccess('Announcement created successfully');

        }
        
        catch(QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch(\Exception $e) {
            return $this->jsonException($e);
        }
    }

    public function update(Request $request, Announcement $announcement)
    {
        try {
            if(!can('update_announcements')){
                return $this->jsonError('You do not have permission to update announcements', 403);
            }

            if(scope('update_announcements') == 'owned' && $announcement->created_by != user()->id){
                return $this->jsonError('You can only update your own announcements', 403);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'action_url' => 'nullable|url',
                'target_roles' => 'nullable|array',
                'target_roles.*' => 'exists:roles,id',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return $this->jsonError($validator->errors()->first(), 422);
            }

            $data = $validator->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($announcement->image) {
                    Storage::disk('public')->delete($announcement->image);
                }
                $imagePath = $request->file('image')->store('announcements', 'public');
                $data['image'] = $imagePath;
            }

            $announcement->update($data);

            $this->announcement = $announcement->load('creator');

            $this->redirect = route('admin.announcements.index');
            return $this->jsonSuccess('Announcement updated successfully');
        }
        
        catch(QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch(\Exception $e) {
            return $this->jsonException($e);
        }
    }

    public function destroy(Announcement $announcement)
    {
        try {
            if(!can('delete_announcements')){
                return $this->jsonError('You do not have permission to delete announcements', 403);
            }

            if(scope('delete_announcements') == 'owned' && $announcement->created_by != user()->id){
                return $this->jsonError('You can only delete your own announcements', 403);
            }

            // Delete associated image if exists
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }

            $announcement->delete();

            return $this->jsonSuccess('Announcement deleted successfully');

        }
        
        catch(QueryException $e) {
            return $this->dbExceptionEvaluator($e);
        }
        
        catch(\Exception $e) {
            return $this->jsonException($e);
        }
    }

    public static function routes()
    {
        Route::get('/', [self::class, 'index'])->name('admin.announcements.index');
        Route::post('/store', [self::class, 'store'])->name('admin.announcements.store');
        Route::post('/update/{announcement}', [self::class, 'update'])->name('admin.announcements.update');
        Route::delete('/destroy/{announcement}', [self::class, 'destroy'])->name('admin.announcements.destroy');
    }
}