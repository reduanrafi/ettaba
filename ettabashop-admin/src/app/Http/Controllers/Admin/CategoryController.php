<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeSection;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
    private $globalObject;
    private $moduleName = "Category";
    private $singularVariableName = 'category';
    private $pluralVariableName = 'categories';

    public function __construct()
    {
        $this->globalObject = new Category();
    }

    private function getHomeSections()
    {
        try {
            if (Schema::hasTable('home_sections')) {
                return HomeSection::where('section_type', 'category')
                    ->orderBy('sort_order', 'asc')
                    ->get();
            }
        } catch (\Throwable $e) {}
        return collect([]);
    }

    private function syncCategoryHomeSections($categoryId, $selectedSectionIds)
    {
        try {
            if (!Schema::hasTable('home_sections')) {
                return;
            }
            $selectedSectionIds = is_array($selectedSectionIds) ? array_map('intval', $selectedSectionIds) : [];
            $sections = HomeSection::where('section_type', 'category')->get();
            foreach ($sections as $section) {
                $catIds = $section->category_ids_array;
                if (in_array((int)$section->id, $selectedSectionIds)) {
                    if (!in_array((int)$categoryId, $catIds)) {
                        $catIds[] = (int)$categoryId;
                    }
                } else {
                    $catIds = array_values(array_diff($catIds, [(int)$categoryId]));
                }
                $section->category_ids = json_encode(array_values(array_unique($catIds)));
                $section->save();
            }
        } catch (\Throwable $e) {}
    }

    public function index()
    {
        $query = Category::query();
        try {
            if (Schema::hasColumn('categories', 'priority')) {
                $query->orderBy('priority', 'asc');
            }
        } catch (\Throwable $e) {}
        $categories = $query->orderBy('id', 'asc')->get();
        $homeSections = $this->getHomeSections();

        return view('admin.' . $this->pluralVariableName . '.index', [
            $this->pluralVariableName => $categories,
            'homeSections' => $homeSections,
        ]);
    }

    public function create()
    {
        $categories = Category::where('is_deleted', 0)->get();
        $homeSections = $this->getHomeSections();

        return view('admin.' . $this->pluralVariableName . '.create', [
            $this->pluralVariableName => $categories,
            'homeSections' => $homeSections,
        ]);
    }

    public function edit($id)
    {
        $data = $this->globalObject->findOrFail($id);
        $homeSections = $this->getHomeSections();

        return view('admin.' . $this->pluralVariableName . '.edit', [
            $this->singularVariableName => $data,
            $this->pluralVariableName => Category::where('is_deleted', 0)->get(),
            'homeSections' => $homeSections,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $this->globalObject->GetData($request->all());
            $created = $this->globalObject->create($data);
            if ($created) {
                if ($request->has('sync_sections')) {
                    $this->syncCategoryHomeSections($created->id, $request->input('home_section_ids', []));
                }
                return redirect()->route('category.index')->with(['success' => 'ক্যাটাগরি সফলভাবে যুক্ত করা হয়েছে!']);
            }
            return redirect()->back()->with(['error' => "Unable to handle this request !"]);
        } catch (QueryException $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $oldData = $this->globalObject->findOrFail($id);
        try {
            $data = $this->globalObject->GetData($request->all());
            if ($oldData->update($data)) {
                if ($request->has('sync_sections')) {
                    $this->syncCategoryHomeSections($oldData->id, $request->input('home_section_ids', []));
                }
                return redirect()->route('category.index')->with(['success' => 'ক্যাটাগরি সফলভাবে আপডেট করা হয়েছে!']);
            }
            return redirect()->back()->with(['error' => "Unable to update"]);
        } catch (QueryException $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }

    public function toggleHide(Request $request)
    {
        try {
            if ($this->globalObject->toggleHide($request)) {
                return redirect()->back()->with(['success' => 'ক্যাটাগরি দৃশ্যমানতা (Show/Hide) আপডেট করা হয়েছে!']);
            }
            return redirect()->back()->with(['error' => "Unable to update"]);
        } catch (QueryException $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }

    public function toggleActive($id)
    {
        try {
            $cat = Category::findOrFail($id);
            $cat->is_active = !$cat->is_active;
            $cat->save();
            return redirect()->back()->with(['success' => 'ক্যাটাগরি সক্রিয়/নিষ্ক্রিয় স্ট্যাটাস আপডেট করা হয়েছে!']);
        } catch (\Throwable $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }

    public function moveOrder(Request $request, $id)
    {
        try {
            $cat = Category::findOrFail($id);
            $dir = $request->get('dir', 'up');
            $currentPriority = (int)($cat->priority ?? $cat->id);

            if ($dir === 'up') {
                $cat->priority = max(1, $currentPriority - 1);
            } else {
                $cat->priority = $currentPriority + 1;
            }
            $cat->save();

            return redirect()->back()->with(['success' => 'ক্যাটাগরির ক্রম পরিবর্তন করা হয়েছে!']);
        } catch (\Throwable $ex) {
            return redirect()->back()->with(['error' => $ex->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            if ($this->globalObject->destroy($id)) {
                return redirect()->back()->with(['success' => $this->moduleName . " deleted successfully"]);
            }
        } catch (QueryException $exception) {
            return redirect()->back()->with(['error' => $exception->getMessage()]);
        }
        return redirect()->back()->with(['error' => "Unable to handle this request !"]);
    }
}
