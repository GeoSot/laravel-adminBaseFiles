<?php

namespace GeoSot\BaseAdmin\App\Jobs;

use GeoSot\BaseAdmin\App\Models\Media\Medium;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Plank\Mediable\Helpers\File;

class CompressImage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var medium
     */
    private $medium;

    /**
     * Create a new job instance.
     *
     * @param Medium $medium
     */
    public function __construct(Medium $medium)
    {
        $this->medium = $medium;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Medium::TYPE_IMAGE !== $this->medium->aggregate_type) {
            return;
        }
        $img = $this->medium;
        $result = $this->makeRequest($img);

        if ($this->isError($result)) {
            $this->handleError($result, $img);

            return;
        }

        $this->handleSuccess($result->json(), $img);
    }

    /**
     * @param Medium $img
     *
     * @return Response
     */
    private function makeRequest(Medium $img)
    {
        $request = Http::timeout(10)->attach('files', $img->contents(), $img->basename);

        return $request->post('http://api.resmush.it/?qlty=80');
    }

    /**
     * @param array  $result
     * @param Medium $img
     */
    private function handleSuccess(array $result, Medium $img): void
    {
        $oldSize = File::readableSize($result['src_size']);

        $content = file_get_contents($result['dest']);

        file_put_contents($img->getAbsolutePath(), $content);

        $newSize = File::readableSize($result['dest_size']);
        Log::info("Image {$img->getKey()}  {$img->filename} was compressed by {$result['percent']}%. Old size:{$oldSize} , new Size: {$newSize}");
    }

    /**
     * @param Response $result
     * @param Medium   $img
     */
    public function handleError(Response $result, Medium $img): void
    {
        $code = $result->failed() ? $result->status() : $result->object()->error;
        $body = $result->failed() ? $result->body() : $result->object()->error_long;
        Log::error("Image {$img->getKey()}  {$img->getAbsolutePath()} was not compressed", ['status' => $code, 'body' => $body]);
    }

    /**
     * @param Response $result
     *
     * @return bool
     */
    public function isError(Response $result): bool
    {
        return $result->failed() || isset($result->object()->error);
    }
}
