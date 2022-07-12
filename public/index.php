<?php

use Lune\App;
use Lune\Database\DB;
use Lune\Database\Model;
use Lune\Http\Middleware;
use Lune\Http\Request;
use Lune\Http\Response;
use Lune\Routing\Route;
use Lune\Validation\Rule;
use Lune\Validation\Rules\Required;

require_once "../vendor/autoload.php";

$app = App::bootstrap();

$app->router->get('/test/{param}', function (Request $request) {
    return json($request->routeParameters());
});

$app->router->post('/test', function (Request $request) {
    return json($request->data());
});

$app->router->get('/redirect', function (Request $request) {
    return redirect("/test");
});

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if ($request->headers('Authorization') != 'test') {
            return json(["message" => "Not authenticated"])->setStatus(401);
        }

        $response = $next($request);
        $response->setHeader('X-Test-Custom-Header', 'Hola');

        return $response;
    }
}

Route::get('/middlewares', fn (Request $request) => json(["message" => "ok"]))
    ->setMiddlewares([AuthMiddleware::class]);

Route::get('/html', fn (Request $request) => view('home', ['user' => 'Manolo']));

Route::post('/validate', fn (Request $request) => json($request->validate([
    'test' => 'required',
    'num' => 'number',
    'email' => ['required_with:num', 'email']
], [
    'email' => [
        'email' => 'DAME EL CAMPO'
    ]
])));

Route::get('/session', function (Request $request) {
    // session()->flash('test', 'test');
    return json($_SESSION);
});

Route::get('/form', fn (Request $request) => view('form'));

Route::post('/form', function (Request $request) {
    return json($request->validate(['email' => 'email', 'name' => 'required']));
});

Route::post('/user', function (Request $request) {
    DB::statement("INSERT INTO users (name, email) VALUES (?, ?)", [$request->data('name'), $request->data('email')]);
    return json(["message" => "ok"]);
});

Route::get('/users', function (Request $request) {
    return json(DB::statement("SELECT * FROM users"));
});

class User extends Model {
    protected array $fillable = ['name', 'email'];
}

Route::post('/user/model', function (Request $request) {
    // $user = new User();
    // $user->name = $request->data('name');
    // $user->email = $request->data('email');
    // $user->save();

    return json(User::create($request->data())->toArray());
});

Route::get('/user/query', function (Request $request) {
    return json(array_map(fn ($m) => $m->toArray(), User::where('name', 'Manolo')));
});

Route::post('/users/{id}/update', function (Request $request) {
    $user = User::find($request->routeParameters('id'));

    $user->name = $request->data('name');
    $user->email = $request->data('email');
    
    return json($user->update()->toArray());
});

Route::delete('/users/{id}/delete', function (Request $request) {
    $user = User::find($request->routeParameters('id'));

    return json($user->delete()->toArray());
});

$app->run();
