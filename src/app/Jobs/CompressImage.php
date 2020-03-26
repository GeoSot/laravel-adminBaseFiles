<?php

namespace GeoSot\BaseAdmin\App\Jobs;

use CURLFile;
use GeoSot\BaseAdmin\App\Models\Media\MediumImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileNotFoundException;

class CompressImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var MediumImage
     */
    private $mediumImage;

    /**
     * Create a new job instance.
     *
     * @param  MediumImage  $mediumImage
     */
    public function __construct(MediumImage $mediumImage)
    {

        $this->mediumImage = $mediumImage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $img = $this->mediumImage;
        $filepath = Storage::disk($img->disk)->path($img->file);

        $mime = mime_content_type($filepath);
        $info = pathinfo($filepath);
        $name = $info['basename'];
        $output = new CURLFile($filepath, $mime, $name);

        $data = ["files" => $output];

        $ch = $this->prepareRequest($data);

        $result = curl_exec($ch);

        $error = null;
        if (curl_errno($ch)) {
            $result = curl_error($ch);
        }
        curl_close($ch);

        if ($error) {
            Log::error("Image {$img->getKey()}  {$img->file} was not compressed");
            return;
        }
        $arr_result = json_decode($result);

// store the optimized version of the image
        $this->handleSuccess($arr_result, $img);
    }

    /**
     * @param  array  $data
     * @return false|resource
     */
    private function prepareRequest(array $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.resmush.it/?qlty=80');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        return $ch;
    }

    /**
     * @param $arr_result
     * @param  MediumImage  $img
     * @throws FileNotFoundException
     */
    private function handleSuccess($arr_result, MediumImage $img): void
    {
        $filepath = Storage::disk($img->disk)->path($img->file);
        $ch = curl_init($arr_result->dest);
        $fp = fopen($filepath, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        $newSize = (float) number_format(Storage::disk($img->disk)->getSize($img->file) / 1048576, 3);
        Log::info("Image {$img->getKey()}  {$img->file} was compressed. Old size:{$img->size_mb} , new Size: {$newSize}");
        $img->save(['size_mb' => $newSize]);
    }
}
