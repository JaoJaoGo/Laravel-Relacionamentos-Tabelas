<?php

use App\Models\{
    User,
    Preference,
    Course,
    Module,
    Lesson,
    Permission,
    Image,
    Comment,
    Tag,
};
use Illuminate\Support\Facades\Route;

Route::get('/one-to-one', function () {
    $user = User::with('preference')->find(2);

    $data = [
        'background-color' => '#fff',
    ];
    
    if($user->preference) {
        $user->preference->update($data);
    } else {
        // $user->preference()->create($data);

        $preference = new Preference($data);
        $user->preference()->save($preference);
    }

    $user->refresh();

    // $user->preference->delete();

    dd($user->preference);
});

Route::get('/one-to-many', function () {
    // $course = Course::create(['name' => 'Curso de Laravel']);
    $course = Course::with('modules.lessons')->first();

    echo $course->name;
    echo '<br>';
    foreach($course->modules as $module) {
        echo "{$module->name} <br>";
        echo '<br>';
        foreach($module->lessons as $lesson) {
            echo "{$lesson->name} <br>";
        }
    }

    // $data = [
    //     'name' => 'Módulo x2'
    // ];
    // $course->modules()->create($data);

    // Module::find(2)->update();

    // $course->modules()->get();
    // $modules = $course->modules;

    // dd($modules);
});

Route::get('/many-to-many', function () {
    // dd(Permission::create(['name' => 'menu_03']));
    $user = User::with('permissions')->find(1);

    // $permission = Permission::first();
    // $user->permissions()->save($permission);
    // $user->permissions()->saveMany([
    //     Permission::find(1),
    //     Permission::find(2),
    //     Permission::find(3),
    // ]);
    // $user->permissions()->sync([1]);
    // $user->permissions()->attach([1, 3]);
    // $user->permissions()->detach([1]);
    
    $user->refresh();

    dd($user->permissions);
});

Route::get('/many-to-many-pivot', function () {
    $user = User::with('permissions')->find(1);
    // $user->permissions()->attach([
    //     1 => ['active' => false],
    //     2 => ['active' => false],
    // ]);

    $user->refresh();

    echo "<b>{$user->name}</b><br>";
    foreach($user->permissions as $permission) {
        echo "{$permission->name} - {$permission->pivot->active} <br>";
    }
});

Route::get('/one-to-one-polymorphic', function () {
    $user = User::find(1);

    $data = ['path' => 'path/nome-image.png'];

    if($user->image) {
        $user->image->update($data);
    } else {
        // $user->image()->save(new Image($data));
        $user->image()->create($data);
    }

    dd($user->image);
});

Route::get('/one-to-many-polymorphic', function () {
    $course = Course::first();

    // $course->comments()->create([
    //     'subject' => 'Novo Comentario',
    //     'content' => 'Apenas um comentario legal!',
    // ]);

    // dd($course->comments);

    $comment = Comment::find(1);
    dd($comment->commentable);
});

Route::get('/many-to-many-polymorphic', function () {
    // $user = User::first();
    // $course = Course::first();

    // Tag::create(['name' => 'tag1', 'color' => 'blue']);
    // Tag::create(['name' => 'tag2', 'color' => 'red']);
    // Tag::create(['name' => 'tag3', 'color' => 'green']);

    // $user->tags()->attach([1, 3]);
    // $course->tags()->attach(2);

    // $user->refresh();
    // $course->refresh();

    // dd($user->tags);
    // dd($course->tags);

    // $tag = Tag::find(3);
    // dd($tag->users);
    // $tag = Tag::find(2);
    // dd($tag->courses);

    $tag = Tag::where('name', 'tag2')->first();
    dd($tag->courses);
});

Route::get('/', function () {
    return view('welcome');
});
