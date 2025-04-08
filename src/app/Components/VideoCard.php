<?php namespace Eirb\Vost\Web\Components;

use Eirb\Vost\Web\Models\Video as Video;

class VideoCard {
  private Video $video;

  function __construct(Video $video) {
    $this->video = $video;
  }

  function toString(): string {
    return <<<EOT
      <a href="/video.php?id={$this->video->id}" class="video-card">
        <div class="thumbnail-container">
          <div class="video-thumb" style="background-image: url('{$this->video->getThumbnail()}');">
            <p class="video-duration">{$this->video->formatDuration()}</p>
          </div>
          <p class="play-button"><i class="fa-solid fa-play" aria-hidden="true"></i></p>
        </div>
        <div class="infos-container">
          <h4>{$this->video->getTitle()}</h4>
          <p>{$this->video->formatPublishedOn()}</p>
        </div>
      </a>
    EOT;
  }
}

?>