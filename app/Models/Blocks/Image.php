<?php


	namespace App\Models\Blocks;


	use App\Models\Block;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;

    class Image extends Block
	{
        protected $table = 'blocks';

        public static ?string $type = 'image';
        public static bool $display = false;

        public static function uploadImage(Request $request, string $imageField, string $image = null)
        {
            if($request->hasFile($imageField)) {
                if($image) Storage::delete($image);
                $folder = date('Y-m-d');
                return $request->file($imageField)->store("images/{$folder}");
            }
            return null;
        }

        public static function getImage(string $name)
        {
            return asset("uploads/$name");
        }
	}
