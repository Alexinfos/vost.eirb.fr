<?php namespace Eirb\Vost\Web\Utils;

class Session {
    private ?int $id = null;
    private ?string $uid = null; /* CAS login */
    private ?string $name = null;
    private ?string $school = null;

    public function __construct(array $session) {
        if (!isset($session['uid'])) {
            return;
        }

        $this->uid = $session['uid'];
        $this->school = (int)$session['school'];

        if (!isset($session['id']) && ctype_digit($session['id'])) {
            return;
        }

        $this->id = (int)$session['id'];
        $this->name = $session['name'];
    }

    public function create(\PDO $database, array $session, string $uid, string $school) {
        $session['uid'] = $uid;
        $session['school'] = $school;

        $this->uid = $uid;
        $this->school = $school;

        $req = $database->prepare('SELECT * FROM `users` WHERE `uid` = ? and `isActive` = 1 LIMIT 1;');
        $req->execute(array($uid));

        $result = $req->fetch();

        if ($result === FALSE) {
            return;
        }

        $session['id'] = (int)$result['id'];
        $session['name'] = $result['name'];
    }

    public function isLoggedIn(): bool {
        return isset($this->uid);
    }

    public function isAdmin(): bool {
        return isset($this->id);
    }

    public function getId(): ?int {
        if (!$this->isLoggedIn()) {
            return null;
        }
        return $this->id;
    }

    public function getDisplayName(): string {
        if (!$this->isLoggedIn()) {
            return "Déconnecté";
        }
        return htmlspecialchars(isset($this->name) ? $this->name : $this->uid);
    }
}

?>