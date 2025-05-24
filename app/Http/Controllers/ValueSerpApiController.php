<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;


class ValueSerpApiController extends Controller
{
          public function index(Request $request)
        {
           //  $apiKey = '9C6F1EBAE04D4CCDB9139A2855E4EF60';
            $apiKey = config('services.valueserp.key');
            $keyword = $request->input('keyword', 'keyword+here');
            $location = '98146,Washington,United+States';
            $page = $request->input('page', 1); 
            $start = ($page - 1) * 10;
        
            $apiUrl = "https://api.valueserp.com/search?api_key={$apiKey}&q={$keyword}&location={$location}&gl=us&hl=en&google_domain=google.com&include_ai_overview=true&start={$start}";

            //$apiUrl = "https://api.valueserp.com/search?api_key=9C6F1EBAE04D4CCDB9139A2855E4EF60&q=keyword+here&location=98146%2C+Washington%2C+United+States&gl=us&hl=en&google_domain=google.com&include_ai_overview=true";
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                ])->get($apiUrl);
            } catch (\Exception $e) {
                return view('value-serp', [
                    'data' => [],
                    'keyword' => $keyword,
                    'currentPage' => $page,
                    'error' => 'Result not found. Please try again later.',
                ]);
            }

            if (!$response->successful()) {
                return view('value-serp', [
                    'data' => [],
                    'keyword' => $keyword,
                    'currentPage' => $page,
                    'error' => 'Result not found. Please try again later.',
                ]);
            }
            $data = $response->json();
            if ($request->has('export') && $request->export == 'csv') {
                $csvData = [];

                if (isset($data['organic_results']) && count($data['organic_results']) > 0) {
                    $csvData[] = ['Title', 'Link', 'Snippet', 'Displayed Link'];
                    foreach ($data['organic_results'] as $result) {
                        $csvData[] = [
                            $result['title'] ?? '',
                            $result['link'] ?? '',
                            $result['snippet'] ?? '',
                            $result['displayed_link'] ?? ($result['link'] ?? '')
                        ];
                    }
                }
                $filename = 'search_results_' . time() . '.csv';
                $handle = fopen('php://temp', 'r+');
                foreach ($csvData as $row) {
                    fputcsv($handle, $row);
                }
                rewind($handle);
                $csvContent = stream_get_contents($handle);
                fclose($handle);

                return Response::make($csvContent, 200, [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename=\"$filename\"",
                ]);
            }

            return view('value-serp', [
                'data' => $data,
                'keyword' => $keyword,
                'currentPage' => $page,
            ]);

        }
}
