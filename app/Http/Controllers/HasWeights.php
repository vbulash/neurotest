<?php


	namespace App\Http\Controllers;

    use App\Models\Block;
    use Illuminate\Http\Request;

    trait HasWeights
	{
	    protected array $weights = [
	        "А" => 0,
            "Б" => 0,
            "В" => 0,
            "Г" => 0,
        ];

	    public function getWeights(Request $request): ?array {
	        if(!$request->has('block_id')) return null;

	        $block = Block::findOrFail($request->block_id);
	        $handler = config('blocks.' . $block->type);
            $content = $handler::$content;

            foreach (['A', 'B', 'C', 'D'] as $letter) {
                if ($request->has('image' . $letter))
                    $this->weights[$letter] = $content['weight' . $letter];
            }

	        return $this->weights;
        }
	}
