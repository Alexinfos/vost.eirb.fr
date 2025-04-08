<?php namespace Eirb\Vost\Web\Models;

class User {
  public int $id;
  public ?string $uid;
  public string $name;
  public ?int $year;
  public ?string $role;
  public ?string $profilePicture;

  public function __construct(array $dbRow) {
    $this->id = $dbRow['id'];
    $this->uid = $dbRow['uid'];
    $this->name = $dbRow['name'];
    $this->year = $dbRow['year'];
    $this->role = ($dbRow['role'] == "") ? null : $dbRow['role'];
    $this->profilePicture = ($dbRow['profilePicture'] == "") ? null : $dbRow['profilePicture'];
  }

  public function getName(): string {
    return htmlspecialchars($this->name);
  }

  public function getRole(): ?string {
    if (!isset($this->role)) {
      return null;
    }
    return htmlspecialchars($this->role);
  }

  public function getProfilePicture(): string {
    if (!isset($this->profilePicture)) {
      return 'default.svg';
    }
    return urlencode($this->profilePicture);
  }
}

?>