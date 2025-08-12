<?php namespace Eirb\Vost\Web\Models;

use \Carbon\Carbon;

class Video {
  public int $id;
  public string $title;
  public string $url;
  public ?string $thumbnail;
  public int $publishedOn;
  public int $year;
  public int $duration; // In seconds
  public bool $visible;

  public function __construct(array $dbRow) {
    $this->id = $dbRow['id'];
    $this->title = $dbRow['title'];
    $this->url = $dbRow['url'];
    $this->thumbnail = ($dbRow['thumbnail'] == "") ? null : $dbRow['thumbnail'];
    $this->publishedOn = $dbRow['publishedOn'];
    $this->year = $dbRow['year'];
    $this->duration = $dbRow['duration'];
    $this->visible = ($dbRow['visible'] == 1);

    Carbon::setLocale('fr');
  }

  public function getUrl(): string {
    return $this->url;
  }

  public function getEmbedUrl(): string {
    return explode("&", str_replace('/watch?v=', '/embed/', $this->url))[0] . '?autoplay=1';
  }

  public function getThumbnail(): string {
    if (!isset($this->thumbnail)) {
      return htmlspecialchars('/assets/thumbs/default.svg');
    }
    return htmlspecialchars($this->thumbnail);
  }

  public function getPlatformName(): string {
    if (str_contains($this->url, "youtube.com")) {
      return "YouTube";
    }
    return "la plateforme";
  }

  public function getTitle(): string {
    return htmlspecialchars($this->title);
  }

  public function formatPublishedOn(): string {
    $dateObj = Carbon::createFromTimestamp($this->publishedOn / 1000);

    return $dateObj->translatedFormat('j F Y');
  }

  public function getPublishedOn(): string {
    return date('Y-m-d', $this->publishedOn / 1000);
  }

  public function formatDuration(): string {
    $seconds = $this->duration % 60;
    $minutes = floor($this->duration / 60) % 60;
    $hours = floor($this->duration / 3600);

    return ($hours > 0 ? $hours . ':' : '') . str_pad($minutes, 2, '0', \STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', \STR_PAD_LEFT);
  }

  public function getDurationHours(): int {
    return floor($this->duration / 3600);
  }

  public function getDurationMinutes(): int {
    return floor($this->duration / 60) % 60;
  }

  public function getDurationSeconds(): int {
    return $this->duration % 60;
  }
}

?>