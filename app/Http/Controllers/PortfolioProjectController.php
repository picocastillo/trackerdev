<?php

namespace App\Http\Controllers;

use App\Models\PortfolioProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PortfolioProjectController extends Controller
{
    public function index()
    {
        $projects = PortfolioProject::ordered()->get();

        return view('portfolio.index', compact('projects'));
    }

    public function create()
    {
        return view('portfolio.create');
    }

    public function edit($id)
    {
        $project = PortfolioProject::findOrFail($id);

        return view('portfolio.create', compact('project'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'badges' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'secondary_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $imagePath = $this->storeImage($request->file('image'));
            $secondaryPath = $request->hasFile('secondary_image')
                ? $this->storeImage($request->file('secondary_image'))
                : null;

            PortfolioProject::create([
                'title' => $request->title,
                'description' => $request->description,
                'badges' => $this->parseBadges($request->badges),
                'image' => $imagePath,
                'secondary_image' => $secondaryPath,
                'sort_order' => (int) ($request->sort_order ?? 0),
                'is_active' => $request->boolean('is_active'),
            ]);

            DB::commit();

            return redirect('/portfolio')->with('alert-success', 'Proyecto de portfolio agregado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('alert-danger', $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $project = PortfolioProject::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'badges' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'secondary_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'remove_secondary_image' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'badges' => $this->parseBadges($request->badges),
                'sort_order' => (int) ($request->sort_order ?? 0),
                'is_active' => $request->boolean('is_active'),
            ];

            if ($request->hasFile('image')) {
                $this->deleteStoredImage($project->image);
                $data['image'] = $this->storeImage($request->file('image'));
            }

            if ($request->boolean('remove_secondary_image')) {
                $this->deleteStoredImage($project->secondary_image);
                $data['secondary_image'] = null;
            } elseif ($request->hasFile('secondary_image')) {
                $this->deleteStoredImage($project->secondary_image);
                $data['secondary_image'] = $this->storeImage($request->file('secondary_image'));
            }

            $project->update($data);

            DB::commit();

            return redirect('/portfolio')->with('alert-success', 'Proyecto de portfolio actualizado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('alert-danger', $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $project = PortfolioProject::findOrFail($id);

        try {
            DB::beginTransaction();

            $this->deleteStoredImage($project->image);
            $this->deleteStoredImage($project->secondary_image);
            $project->delete();

            DB::commit();

            return redirect('/portfolio')->with('alert-success', 'Proyecto de portfolio eliminado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('alert-danger', $e->getMessage());
        }
    }

    private function parseBadges(?string $badges): array
    {
        if ($badges === null || trim($badges) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $badges))));
    }

    private function storeImage($file): string
    {
        $path = $file->store('portfolio', 'public');

        return '/storage/' . $path;
    }

    private function deleteStoredImage(?string $path): void
    {
        if (!$path || !str_starts_with($path, '/storage/')) {
            return;
        }

        $relative = ltrim(str_replace('/storage/', '', $path), '/');
        if (Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }
}
