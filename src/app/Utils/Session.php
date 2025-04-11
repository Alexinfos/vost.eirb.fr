<?php namespace Eirb\Vost\Web\Utils;

class Session {
    private ?int $id = null;
    private ?string $uid = null; /* CAS login */
    private ?string $name = null;
    private ?string $school = null;

    public function __construct(array $session, \PDO $database) {
        if (!isset($session['cas_data'])) {
            return;
        }

        $this->uid = $session['cas_data']->uid;
        $this->school = $session['cas_data']->ecole;

        if (!isset($session['id']) || !ctype_digit($session['id'])) {
            $req = $database->prepare('SELECT * FROM `users` WHERE `uid` = ? and `isActive` = 1 LIMIT 1;');
            $req->execute(array($this->uid));

            $result = $req->fetch();

            if ($result === FALSE) {
                return;
            }

            $session['id'] = (int)$result['id'];
            $session['name'] = $result['name'];
        }

        $this->id = (int)$session['id'];
        $this->name = $session['name'];
    }

    public function initVostMember() {
        
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