<?php

namespace Deaduseful\WhoisParser;

use App\Whois;
use Deaduseful\WhoisParser\src\Parser;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view(ServiceProvider::NAMESPACE . '::index');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $lookup = $request->get('lookup');
        if (empty($lookup)) {
            $query = $request->get('query');
            $whois = Whois::where(['query' => $query])->firstOrFail();
            $lookup = $whois->lookup;
        }
        $parser = new Parser($lookup);
        $result = $parser->getOutput();
        // return a proper json response
        return response()->json($result);
    }
}
