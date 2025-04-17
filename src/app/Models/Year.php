<?php namespace Eirb\Vost\Web\Models;

class Year {
  public int $id;
  public string $name;
  public ?string $groupPicture;
  public ?int $memberCount;

  public function __construct(array $dbRow) {
    $this->id = $dbRow['id'];
    $this->name = $dbRow['name'];
    $this->groupPicture = ($dbRow['groupPicture'] == "") ? null : $dbRow['groupPicture'];
    $this->memberCount = !isset($dbRow['memberCount']) ? null : $dbRow['memberCount'];
  }

  public function getName(): string {
    return htmlspecialchars($this->name);
  }

  public function getFirstYear(): string {
    return htmlspecialchars(explode("-", $this->name)[0]);
  }

  public function getSecondYear(): string {
    return htmlspecialchars(explode("-", $this->name)[1]);
  }

  public function getGroupPicture(): string {
    if (!isset($this->groupPicture)) {
      return urlencode('default.svg');
    }
    return urlencode($this->groupPicture);
  }

  public function getUsers(\PDO $database): array {
    $req = $database->prepare('SELECT * FROM `users` WHERE `year` = ? ORDER BY `name` ASC;');
    $req->execute(array($this->id));

    $userList = array();
    while ($u = $req->fetch()) {
      $user = new User($u);
      array_push($userList, $user);
    }

    return $userList;
  }

  public function getVideos(\PDO $database): array {
    $req = $database->prepare('SELECT * FROM `videos` WHERE `year` = ? ORDER BY `publishedOn` ASC;');
    $req->execute(array($this->id));

    $videoList = array();
    while ($v = $req->fetch()) {
      $video = new Video($v);
      array_push($videoList, $video);
    }

    return $videoList;
  }
}

?>