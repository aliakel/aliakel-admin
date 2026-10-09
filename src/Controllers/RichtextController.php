<?php

namespace AliAkel\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RichtextController extends Controller
{
    /**
     * Store an image inserted from the rich text field.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function image(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $disk = $this->disk();
        $directory = trim((string) config('admin.upload.directory.image', 'images'), '/').'/richtext';
        $name = Str::uuid()->toString().'.'.$request->file('image')->extension();
        $path = $request->file('image')->storeAs($directory, $name, $disk);

        return response()->json([
            'url' => Storage::disk($disk)->url($path),
        ]);
    }

    /**
     * Disk that can serve a public URL.
     *
     * When the configured upload disk was never added to filesystems.php,
     * files are written under public/uploads so the returned URL is reachable.
     */
    protected function disk(): string
    {
        $configured = (string) config('admin.upload.disk', 'admin');

        if (config('filesystems.disks.'.$configured)) {
            return $configured;
        }

        config([
            'filesystems.disks.admin' => [
                'driver' => 'local',
                'root' => public_path('uploads'),
                'url' => '/uploads',
                'visibility' => 'public',
                'throw' => false,
            ],
        ]);

        return 'admin';
    }
}
