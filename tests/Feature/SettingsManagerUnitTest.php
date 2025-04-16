<?php

use DanieleMontecchi\LaravelScopedSettings\Managers\SettingsManager;
use DanieleMontecchi\LaravelScopedSettings\Models\Setting;
use Illuminate\Database\Eloquent\Model;

beforeEach(function () {
    Setting::truncate();
    $this->manager = new SettingsManager;
});

it('can set and get global values', function () {
    $this->manager->set('app.locale', 'en');
    expect($this->manager->get('app.locale'))->toBe('en');
});

it('can override existing global value', function () {
    $this->manager->set('app.debug', false);
    $this->manager->set('app.debug', true);
    expect($this->manager->get('app.debug'))->toBeTrue();
});

it('can forget a global setting', function () {
    $this->manager->set('api.token', 'abc123');
    $this->manager->forget('api.token');
    expect($this->manager->get('api.token'))->toBeNull();
});

it('can handle array values', function () {
    $array = ['foo' => 'bar', 'baz' => 123];
    $this->manager->set('settings.array', $array);
    expect($this->manager->get('settings.array'))->toBe($array);
});

it('can handle boolean and numeric values', function () {
    $this->manager->set('flag.enabled', true);
    $this->manager->set('max.count', 10);

    expect($this->manager->get('flag.enabled'))->toBeTrue()
        ->and($this->manager->get('max.count'))->toBe(10);
});

it('can use scoping with models', function () {
    $user = new class extends Model {
        public $id = 1;
        protected $table = 'test_users';

        public function getKey()
        {
            return $this->id;
        }
    };

    $scoped = $this->manager->for($user);
    $scoped->set('profile.language', 'it');

    expect($scoped->get('profile.language'))->toBe('it')
        ->and($this->manager->get('profile.language'))->toBeNull();
    // non scoped
});

it('can clear scope and fallback to global settings', function () {
    $this->manager->set('ui.theme', 'dark');

    $user = new class extends Model {
        public $id = 1;
        protected $table = 'test_users';

        public function getKey()
        {
            return $this->id;
        }
    };

    $scoped = $this->manager->for($user);
    $scoped->set('ui.theme', 'light');

    expect($scoped->get('ui.theme'))->toBe('light');

    $cleared = $scoped->clearScope();
    expect($cleared->get('ui.theme'))->toBe('dark');
});

it('returns all settings as flat keys for a model', function () {
    $user = new class extends Model {
        public $id = 99;
        protected $table = 'test_users';
        public function getKey() { return $this->id; }
    };

    $manager = $this->manager->for($user);
    $manager->set('notifications.email', true);
    $manager->set('profile.name', 'Daniele');

    expect($manager->all())->toBe([
        'notifications.email' => true,
        'profile.name' => 'Daniele',
    ]);
});

