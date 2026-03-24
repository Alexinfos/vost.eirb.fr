<?php namespace Eirb\Vost\Web\Models;

class HomeSection {
  public int $id;
  public string $title;
  public string $content;

  public function __construct(array $dbRow) {
    $this->id = $dbRow['id'];
    $this->title = $dbRow['title'];
    $this->content = $dbRow['content'];
  }

  public function getTitle(): string {
    return htmlspecialchars($this->title);
  }

  public function getContent(): string {
    return $this->content; // HTML is allowed in section content (!)
  }
}

?>