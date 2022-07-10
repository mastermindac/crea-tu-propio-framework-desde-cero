<?php

namespace Lune\Session;

class Session {
    protected SessionStorage $storage;

    public const FLASH_KEY = '_flash';

    public function __construct(SessionStorage $storage) {
        $this->storage = $storage;
        $this->storage->start();

        if (!$this->storage->has(self::FLASH_KEY)) {
            $this->storage->set(self::FLASH_KEY, ['old' => [], 'new' => []]);
        }
    }

    public function __destruct() {
        foreach ($this->storage->get(self::FLASH_KEY)['old'] as $key) {
            $this->storage->remove($key);
        }
        $this->ageFlashData();
        $this->storage->save();
    }

    public function ageFlashData() {
        $flash = $this->storage->get(self::FLASH_KEY);
        $flash['old'] = $flash['new'];
        $flash['new'] = [];
        $this->storage->set(self::FLASH_KEY, $flash);
    }

    public function flash(string $key, mixed $value) {
        $this->storage->set($key, $value);
        $flash = $this->storage->get(self::FLASH_KEY);
        $flash['new'][] = $key;
        $this->storage->set(self::FLASH_KEY, $flash);
    }

    public function id(): string {
        return $this->storage->id();
    }

    public function get(string $key, $default = null) {
        return $this->storage->get($key, $default);
    }

    public function set(string $key, mixed $value) {
        return $this->storage->set($key, $value);
    }

    public function has(string $key): bool {
        return $this->storage->has($key);
    }

    public function remove(string $key) {
        return $this->storage->remove($key);
    }

    public function destroy() {
        return $this->storage->destroy();
    }
}
