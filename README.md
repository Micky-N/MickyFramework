


# MickyFramework    
  
> *Framework MVC et HMVC par Micky_N version 0.1*  
  
Framework inspirée de Laravel, modulable en structure MVC et/ou HMVC et utilise un moteur de Template Mky.


## Sommaire

- [Structure](#structure)
    - [Dossiers](#dossiers)
    - [Point de configuration](#point-de-configuration)
    - [Routes](#routes)
    - [Providers](#providers)
 - [Application](#application)
   - [Model](#model)
   - [Controller](#controller)
   - [Middleware](#middleware)
   - [Voter](#voter)
   - [Event](#event)
   - [Listener](#listener)
   - [Notification](#notification)
   - [Router](#router)
 - [Vue](#vue)
   - [MkyDirective](#mkydirective)
   - [Liste des directives](#liste-des-directives)
   - [MkyFormatter](#mkyformatter)
 - [MkyCommand CLI](#mkycommand-cli)



  
## Structure  
  
 ### Dossiers  
- app  
   - Events : contient  les évènements à déclencher  
   - Listeners : contient  les écouteurs d'évènement  
   - Http  
      - Controllers : contient  les contrôleurs  
      - Middlewares : contient  les middlewares  
   - MkyDirectives : contient  les méthodes personnalisées pour le moteur de template Mky  
   - MkyFilters : contient  les filtres personnalisées pour le moteur de template Mky  
   - Models : contient  les modèles de la base de données MYSQL  
   - Notifications : contient  les systèmes de notifications  
   - Providers : contient le MiddlewareServiceProvider.php, l'EventServiceProvider.php, le MkyServiceProvider.php et le Provider.php  
   - Voters : contient Les voters  
   - En mode HMVC, les dossiers des modules se situent dans le dossier app/Nom_du_module)
     
- config : contient les fichiers de configuration  
  
- public : contient les fichiers index.php et les css et js  
  
- routes : contient les fichiers de route, web.yaml, admin.yaml et functions.php  
  
- views : contient toutes les vues .mky

### Point de configuration

Dans le dossier /config

| fichier | Point de Config| description |
|--|--|--|
| app | app_name | nom de l'application |
|| cache | dossier de cache |
|| env | environnement d'application |
|| structure | structure de l'application (MVC ou HMVC) |
|| debugMode | activation de la barre de debug |
| database | connections.mysql | données de connexion à MYSQL |
| mkyEngine | cache | sous-dossier de cache pour les vues compilées |
| module | views | dossier de base pour les vues .mky |
|| layouts| dossier de base pour les templates .mky |

### Routes

L'application récupère toutes les routes dans le dossier /routes/*.yaml, les routes sont écrites en format .yaml ainsi que les fonctions de route personnalisées dans le fichier functions.php
```YAML
# Fichier web.yaml
todos:  
    index:  
        path: /todos
        action: App\Http\Controllers\TodoController::index  
        method: GET
        middleware: # Optionnel
            - auth
            - can: edit, todo
```

| Paramètre | Valeur |
|--|--|
| Nom de la route | todos.index |
| chemin de l'url | /todos|
| action (contrôleur::méthode) | App\Http\Controllers\TodoController::index |
| méthode de requête HTTP | GET |
| middleware (en option) | RouteMiddlewares et/ou Voters |

Seul les routes situées dans le fichier admin.yaml sont prefix (nom et chemin de l'url) par admin exemple : nom = admin.todos.index et path = /admin/todos.

Pour utiliser les fonctions de route dans le fichier functions.php l'action doit être prefixer par func::*nom_de_la_fonction* exemple : func::getUser
```php
// routes/functions.php
// path: test/Micky, action: func::getUser
[  
    'getUser' => function ($username) {  
  	    echo("user ".$username); // user Micky
    },
    'otherFunction' => function () {  
        echo("otherFunctionReturn");  
    }, 
];
```

pour implémentation des 7 methodes CRUD:
```yaml
users:  
    path: crud  
    controller: App\Http\Controllers\UserController
    middleware: auth
  # only: [index, create] pour implémenter uniquement les méthodes index() et create()
```
|request             |path                |controller          |method              |name                |middleware
|--|--|--|--|--|--
|GET                 |/users              |App\Http\Controllers\UserController|index               |users.index         |auth
|GET                 |/users/:user             |App\Http\Controllers\UserController|show                |users.show          |auth
|GET                 |/users/new               |App\Http\Controllers\UserController|new                 |users.new           |auth
|GET                 |/users/edit/:user        |App\Http\Controllers\UserController|edit                |users.edit          |auth
|GET                 |/users/delete/:user      |App\Http\Controllers\UserController|delete              |users.delete        |auth
|POST                |/users              |App\Http\Controllers\UserController|create              |users.create        |auth
|POST                |/users/update/:user      |App\Http\Controllers\UserController|update              |users.update        |auth


### Providers

Les providers sont des enregistrements de classes dans un but définie.
- EventServiceProvider

Ce provider sert à stocker les events et leurs listeners selon leur actions sont forme de tableau :
```php
[
    \App\Events\TodoEvent::class => [  
	'update' => \App\Listeners\UpdateTodoListener::class,
        'otherAction' => \App\Listeners\OtherListener::class,  
    ]
];
```

- MiddlewareServiceProvider

Ce provider sert à stocker les middleware de route avec un alias, et les voters :
```php
[  
    'routeMiddlewares' => [  
        'todo' => \App\Http\Middlewares\TodoMiddleware::class  
    ],  
  
    'voters' => [  
	 \App\Voters\TodoVoter::class,  
    ],  
];
```

- MkyServiceProvider

Ce provider sert à stocker les fonctions et les formats personnalisés pour le moteur de template Mky : 
```php
[  
    'formatters' => [ 
  	 App\MkyFormatters\TestFormatters::class
    ],  
    'directives' => [
         App\MkyDirectives\TestDirective::class  
    ]
];
```

- Provider

Ce provider sert à stocker des classes pour des utilisations spéciaux, comme des systèmes de notification, les classes sont stockées avec des alias :
```php
[  
    'alias' => [  
        'webPush' => \App\Utils\WebPushNotification::class  
    ]
];
```

## Application

### Model

- Mysql

La classe abstraite Core\Model utilise la classe PDO pour effectuer des requêtes dans la base de données MYSQL, elle utilise aussi un trait QueryMysql qui sert de Query Builder pour simplifié l'écriture des requêtes SQL.

Les classes héritant de Model doivent saisir : 
- Le nom de la table : `protected string $table`
(En anglais) Si le nom de la table est le pluriel du nom de la classe model alors définir le nom de la table est optionnel ex: model Todo => table todos sinon saisir le nom de la table `protected string $table = 'todolist';`

 - La clé primaire `protected string $primaryKey (si clé primaire != 'id')` 
 - En option, les champs de création et de modification d'enregistrement `protected array $dateTimes` ainsi que les champs saisissable `protected array $settable`
 
#### Liste des méthodes:

- Model: 

```php
ex: 
// Déclaration de classe
Todo extends Core\Model;
protected string $table= 'todolist';
protected string $primairKey = 'id';
protected array $dateTimes = ['CREATED_AT' => 'created_todo_at', 'UPDATED_AT' => 'updated_todo_at'];
protected array $settable = ['task', 'completed', 'created_todo_at', 'updated_todo_at'];
// Instanciation de la classe Todo
$todo = new Todo();

/*
 * Retourne le nom de la table en base de données
 */
 public function getTable();  
  
/*
 * Retourn le nom de la clé primaire
 */
 public function getPrimaryKey();  
  
/* 
 * Retourne le model actuellement instantié
 */
 public static function getCurrentModel();  
  
/*
 * Retourne l'engistrement grâce à la valeur de sa clé primaire
 */
 public static function find($id);  
  
/*
 * Retourne tous les enregistrements
 */
 public static function all();  
  
/* 
 * Retourne le nombre d'enregistrement
 */
 public static function count();  
  
/* 
 * crée un nouvel enregistrement 
 */
 public static function create(array $data, string $table = '');  
  
/*  
 * Enregistre l'object créer à partir de l'instance du model
 * 
 * $todo = new Todo();
 * $todo->task = 'Coder'; $todo->completed = 1;...
 * $todo->save();
 */
 public function save();  
  
/*
 * Modifie un enregistrement  
 */
 public static function update($id, array $data);

/*
 * Modifie un enregistrement à partir l'object
 * 
 * $todo = Todo::find(1);
 * $todo->task = 'Coder_Modifié'; $todo->completed = 0;...
 * $todo->modify();
 */
 public function modify(array $data = []);  
  
/*
 * Supprime un enregistrement
 */
 public static function delete($id);
  
/*
 * Supprime l'enregistrement à partir de son instance
 * 
 * $todo = Todo:find(1); // $todo = instance de Todo
 * $todo->destroy()
 * Todo::find(1) => null 
 */
 public function destroy();
  
/*
 * Retourne au hasard une valeur de clé primaire de la table
 */
 public static function shuffleId();
  
/*
 * Retourne tous les enregistrements de la table en relation OneToMany
 * 
 * A utiliser dans la classe du model en cours:
 *  
 * public function todos(){ return $this->hasMany(Todo::class, $fk); } => $user->todos
 * si la clé etrangère == nom de la table (au singulier anglais) suffixé par '_id' alors $fk est faculatif
 */
 protected function hasMany(string $model, string $foreignKey = '');
  
/*
 * Retourne l'enregistrement de la table par la clé primaire
 * 
 * A utiliser dans la classe du model en cours: 
 * public function user(){ return $this->belongsTo(User::class, $fk); } => $todo->user
 * si la clé etrangère == nom de la table (au singulier anglais) suffixé par '_id' alors $fk est faculatif
 */
 protected function belongsTo(string $model, string $foreignKey = '');
  
/*
 * Retourne tous les enregistrements de la table par la clé primaire
 * relation ManyToMany
 * 
 * A utiliser dans la classe du model en cours: 
 * public function categories(){ return $this->belongsToMany(Category::class, $pivot, $pk1, $pk2); } => $todo->categories
 * si la table d'association == todo_category ou category_todo l'argument $pivot est facultatif
 * si la clé primaire 1 == (nom de la classe en cours suffixé par _id) ex:todo_id alors $pk1 est facultative
 * si la clé primaire 2 == (nom de la classe du 1er argument suffixé par _id) ex:category_id alors $pk2 est facultative
 */
 protected function belongsToMany(string $model, string $pivot = '', string $primaryKeyOne = '', string $primaryKeyTwo = '');
  
/*
 * Retourne l'enregistrement de la table par la clé primaire
 * relation OneToOne
 * 
 * A utiliser dans la classe du model en cours: 
 * public function user(){ return $this->hasOne(User::class, $fk); } => $todo->user
 * si la clé etrangère == nom de la table (au singulier anglais) suffixé par '_id' alors $fk est faculatif
 */
 protected function hasOne(string $model, string $foreignKey = '');
  
/*
 * Retourne les enregistrements de la table en relation et
 * les champs selectionnés
 */
 public function with(string $relation, array $properties = []);
  
/*
 * Multiple modification des enregistrement 
 * de la table en relation OneToMany
 */
 public function modifyManyRelation(string $relation, string $id, array $data);  
  
/* 
 * Create new record in the relation table
 * Crée un nouvel enregistrement dans la table en relation
 */
 public function attach($table, array $data);
```

- QueryMysqlBuilder:

```php
@method static \Core\QueryBuilderMysql where($args) // 
// si 3 arguments, Todo::where('task', '!=', 'Coder') => WHERE task != 'Coder', 
// si 2 arguments, Todo::where('task', 'Coder') => WHERE task = 'Coder'

@method static \Core\QueryBuilderMysql select($args)
// Todo::select('id', 'task') => SELECT id, task...

@method static \Core\QueryBuilderMysql from(string $table, $alias = null)
// Todo::from('todolist', 'tdl') => FROM todolist (si $alias non null: AS tdl), utile si la table désiré est différente de la table du model en cours, sinon ne pas utiliser from() car elle est sous-entendu

@method static \Core\QueryBuilderMysql join(string $join_table, string $on, string $operation, string $to, string $alias = '')
// Todo::join('users', 'user_id', '=', 'id', 'usr') => JOIN users (si $alias non null: AS usr) ON todo.user_id = users.id (ou avec alias usr.id) 

@method static \Core\QueryBuilderMysql first()
//Todo::first() => ORDER BY id ASC LIMIT 1, recupère le premier enregistrement de la table du model

@method static \Core\QueryBuilderMysql query(string $statement)
// Todo::query('SELECT ....') => execute un requête

@method static \Core\QueryBuilderMysql prepare(string $statement, array $attribute)
// Todo::prepare('INSERT INTO ....', [...]) => execute un requête preparé avec le paramètre

@method static \Core\QueryBuilderMysql orderBy($args)
// Todo::orderBy('id') => ORDER BY id, si orderBy('id', 'DESC') => ORDER BY id DESC

@method static \Core\QueryBuilderMysql limit($args)
// Todo::limit(1) => LIMIT 1 ou Todo::limit(1,2) => LIMIT 1 OFFSET 2

@method static \Core\QueryBuilderMysql groupBy($args)
// Todo::groupBy('completed') => GROUP BY completed

@method static array map(string $key, $value = null)
// Todo('task', $value) => réorganise tous les enregistrements en tableau avec comme clé la valeur $key ('task')
// si $value == null alors $value = tous les champs de l'enregistrement
// si $value == type string ex: 'user_id' alors le tableau sera composé de task => user_id (Coder => USR001)
// si $value == type array ex: [user_id, completed] alors 'Coder' => [USR001, true]

@method static array get() // récupère les enregistrement suivant la requête passé
// Par défaut Todo::get() => SELECT * FROM todolist
// Possible de chainer les methodes pour finir avec get()
// ex: Todo::select('task', 'username')->join('users', 'user_id', 'id')->where('completed', true)->get()

@method static array toArray()
// Transforme l'instance en tableau associatif

@method static \Core\Model|bool last()
// Todo::last() => Récupère le dernier enregistrement de la table en cours

@method static string stringify()
// Todo::select('task', 'created_todo_at')->where('completed', true)->stringify()
// retourne la requête en string 'SELECT task, created_todo_at FROM todolist WHERE completed = 1'
```

### Middleware

Les middlewares implémentent l'interface Core\Interfaces\MiddlewareInterface avec la méthode 
process(callable  $next, ServerRequestInterface  $request) qui contrôle la requête passée par l'utilisateur et exécute une action, généralement une redirection ou renvoi la requête à un autre middleware ou sinon au contrôleur. les RouteMiddlewares doivent être inscrit dans le MiddlewareServiceProvider.php et sont à utiliser dans les routes:
```yaml
# Fichier web.yaml
categories:  
    index:  
        path: /categories  
        action: App\Http\Controllers\CategoryController::index  
        method: GET
        middleware: auth
```
Il peut être aussi utiliser dans l'application avec la méthode static Core\Middleware::run(App\Http\Middlewares\AuthMiddleware::class) ou pour utiliser plusieurs middlewares à la suite Core\Middleware::run([middleware1, middleware2,...])

### Voter

Inspirée de Symfony, le Voter est un système de permission qui gère les accès et possibilités au utilisateurs ([Voir plus](https://grafikart.fr/tutoriels/permissions-php-voter-1323))

Les Voters implémentent le Core\Interfaces\VoterInterface avec les méthodes `canVote(string  $permission, $subject = null)` qui vérifie si le Voter peut voter et `vote($user, string  $permission, $subject = null)` qui récupère le vote du Voter. Les voters doivent être inscrit dans le MiddlewareServiceProvider.php
les Voters peut-être utilisés avec les routes comme RouteMiddleware avec un suffixe can ex: `middleware: can:permission, sujet`, le middleware utilisera la méthode `Core\Permission::can(string  $permission, $subject = null)` et utilisera l'utilisateur connecté, ils peuvent être utilisés dans l'application avec la méthode `Core\Permission::authorize($user, string  $permission, $subject = null)` ou le $user peut être n'importe quel utilisateur, le retour sera un booléen utilise pour les vues pour afficher un élément selon les droits d'utilisation.


### Event

Les events sont des classes qui émettent des évènements depuis un contrôleur, ils implémentent l'interface Core\Interfaces\EventInterface et héritent de Core\Event pour utilise la méthode :
`TodoEvent::dispatch($target = null, $actions = null, array $params = [])` exemple :
`$update_todo => instance de Todo modifiée`
`App\Events\TodoEvent::dispatch($update_todo, ['update'], ['user' => $user])`.

Les actions sont ce qui feront le lien entre l'event et ses listeners, pour que l'application sache quel listener appeler l'event doit être renseigné dans l'EventServiceProvider.php avec les listener associés ex:
[event => ['action' => listener]]
```php
[
    \App\Events\TodoEvent::class => [  
	 'update' => \App\Listeners\UpdateTodoListener::class,
	 'otherAction' => \App\Listeners\OtherListener::class,  
    ]
];
```

### Listener

Les listeners implémentent l'interface Core\Interfaces\ListenerInterface. Le listener reçoit l'instance de l'event qui émet l'évènement (sujet, action et les paramètres) dans la méthode `handle($event)` traite et éxecute une action ex: envoi de mail.

### Notification

- Les notifications 
Les classes notifications implémentent l'interface Core\Interfaces\NotificationInterface. une notification sert à notifier un utilisateur à travers un système (généralement depuis la base de données) la classe qui sera notifié (généralement le model User) doit utiliser le trait Core\Traits\Notify.
La méthode via() permet de renseigner le système ou les systèmes de notification en indiquant leur alias, renseigné dans le Provider.php :
```php
//via($notifiable){ return ['webPush']; }
[  
    'alias' => [  
        'webPush' => \App\Utils\WebPushNotification::class  
    ]
];
```
L'alias renseigné dans la méthode via doit avoir une méthode nommé to suivi du nom de l'alias avec la première lettre en majuscule ex : `toWebPush($notifiable)` qui créera le message selon le format du système renseigné par l'alias.

- Les systèmes de notification

Les systèmes de notification doivent implémenter l'interface Core\Interfaces\SystemNotificationInterface, le système se chargera d'envoyer la notification au utilisateurs autorisés.

### Router

Les méthodes de route:
```php
@method static bool namespaceRoute(string $route = '')
///todos/update/5 => todos

@method static array routesByName()
// retourne la liste des routes en tableau par leur nom

@method static \Core\Route[] getRoutes()
// retourne la liste des routes en tableau par les méthodes de requête

@method static string generateUrlByName(string $routeName, array $params = [])
// retourne l'url de la route grâce au nom et au paramètres de la route
// Route::generateUrlByName('todos.update', ['id' => 1]) => /todos/update/1

@method static bool|string currentRoute(string $route = '', bool $path = false)
// retourne la route actuelle si pas de $route saisie
// si $route alors vérifie si la route actuel == $route
// l'argument $path si true signifie que la $route est un lien et vérifie le lien $route avec le lien actuel
// si $path = false (par défaut) alors $route est un nom de route et vérifie la route

@method static bool isModuleRoute()
// vérifie si la route appartient à un module

@method static \Core\Router redirectName(string $name)
// redirection avec le nom de la route

@method static \Core\Router redirect(string $url)
// redirection avec l'url de la route

@method static \Core\Router back()
// redirection à la page précédente

@method static \Core\Router withError(array $errors)
// redirection avec un message flash d'erreur dans la session
// dans le controller: Route::back->withError(['wrong' => 'tout est faux'])
// dans la vue: {{ $errors['wrong'] }}

@method static \Core\Router withSuccess(array $success)
// redirection avec un message flash de succès dans la session
// dans le controller: Route::back->withSuccess(['right' => 'tout est juste'])
// dans la vue: {{ $success['right'] }}

@method static \Core\Router with(array $messages)
// redirection avec un message flash dans la session
// dans le controller: Route::back->with(['thanks' => 'Merci pour votre lecture'])
// dans la vue: {{ $flashMessage['thanks'] }}
```

## Vue

Les vues sont préfixées par l'extension .mky (index.mky), le dossier des vues sont par défaut dans /views, cela peut être modifié dans la config module.php et le cache des vues compilées, par défaut dans /cache/views peut être modifié dans la config mkyEngine.php.

pour afficher une vue utiliser la méthode Core\Facades\View::render(string $view, array $params = [])
le nom des $view s'écrit avec un point todos.index: dossier /views/todos/index.mky et les variables passées en tableau ['todo' => $todo] ou compact('todo').

### MkyDirective

Inspirée de Twig et Jsp (java), les directives s'écrivent dans la vue avec des balises (comme du html) :
```html
<mky:if cond="$todo->task == 'Coder'">
	<div>true</div>
	<mky:else />
	<div>false</div>
</mky:if>
```
Si la condition est vrai alors il affichera le texte true sinon false, il existe 2 types de directives : longue portée et courte portée. Les longues portées englobe le code html pour le transformer, ex: if, each, repeat... ils s'écrivent 
`<mky:directive params="">...html...</mky:directive>` 
et les courtes portées affichent un resultat ex: route, json, ... et s'écrivent
`<mky:directive params="" />` 
pour les paramètres les guillemets ne sont pas obligatoire.

les vues .mky utilisent le système d'**extends**, **yield** et **sections**, avec la directive **include** la vue peut intégrer d'autres vues, les vue inclues héritent des variables des parents et peuvent recevoir des variables personnalisées.
```html
// views/layouts/template.mky
-- HEADER HTML --
	<mky:yield name="content"/>
-- FOOTER HTML --
```
```html
// views/todos/index.mky
<mky:extends name="nom du layouts ex: template" />
<mky:section name="content">
-- HTML --
	<mky:include name="includeView" data="['var' => 'variable1']"/>
</mky:section>
```


### Liste des directives

```yaml
assets: courte
script: court si src, sinon longue
style: court si href, sinon longue
if: longue
elseif: courte
else: courte
each: longue
repeat: longue
switch: longue
case: courte
break: courte
default: courte
dump: courte
permission: longue
notpermission: longue
auth: longue
json: courte
currentRoute: longue
route: courte
```
Pour créer des directives, déclarer une classe qui implémente l'interface Core\Interfaces\MkyDirectiveInterface et étendre de la classe Core\MkyCompiler\MkyDirectives\Directive. La classe doit être inscrit dans le MkyServiceProvider.php
```php
class TestDirective extends Directive implements MkyDirectiveInterface  
{  
  
    public function getFunctions()  
    {  
        // déclarer les fonctions dans le tableau
        return [  
            'shortTest' => [[$this, 'shortTest']], // pour les courtes directives
            'longTest' => [[$this, 'longTest'], [$this, 'endlongTest']] // pour les longues directives
        ];  
    }  
    
    // implémenter les fonctions
    public function shortTest($int)  
    {  
        // $var = 10;
	    // dans la vue: <div><mky:shortTest int="$var"/></div>
	    // devient <div>$var = 15 (10 + 5)</div>
	    // pour récuperer le nom de la variable passée en paramètres: $this->getRealVariable($int) => $var
        return sprintf('%s = %s (%s + 5)', $this->getRealVariable($int), $int + 5, $int);  
    }

    // <mky:longTest cls="customClass">-- HTML --</mky:longTest>
    // devient <div class="customClass">-- HTML --</div>
    public function longTest($cls)  { return '<div class="'.$cls.'">'; }
    public function endlongTest()   { return '</div>'; }
}
``` 

### MkyFormatter

Les formatters permettent de modifier les variables php dans la vues et s'ecrivent avec un # devant la variable 
`{{ $var#euro }}`,
si le formatter 'euro' permet de mettre un chiffre en format devise en euro alors si  $var = 5 alors `$var#euro => 5,00 €`.
Pour créer des formatters, déclarer une classe qui implémente Core\Interfaces\MkyFormatterInterface.
La classe doit être inscrit dans le MkyServiceProvider.php

```php
class ArrayFormatter implements MkyFormatterInterface  
{  
  
    public function getFormats()  
    {  
        // déclarer le fonctions dans le tableau
        return [  
            'join' => [$this, 'join'],  
            'count' => [$this, 'count']  
        ];  
    }  
  
    // implémenter les fonctions
    public function join(array $array, string $glue = ', ')  
    {  
	    // $var = [1,5,3]; {{ $var#join('!') }} => 1!5!3
        return join($glue, $array);  
    }  
  
    public function count(array $array)  
    {  
        // $var = [1,5,3]; {{ $var#count }} => 3
        return count($array);  
    }  
}
```

## MkyCommand CLI

Les commandes CLI permettent de générer de fichiers en ligne de commande ex: 
`php micky --create=controller --name=TodoController` 
Le fichier TodoController.php est créé dans le dossier app/Http/Controllers
La liste des commandes est disponible en faisant la commande:
`php micky --help`
Liste des commandes :
```yaml
create:
    module: 
        name: required  		# nom du module

    controller: 
        name: required			# nom du controller (suffixer par Controller)
        crud: optional			# (pas de value : --crud) implémentation des 7 methodes CRUD
        path: optional			# sous-dossier du controller (App/Http/Controllers/Sous_dossier/TestController.php)
        module: optional		# (pour HMVC) nom du module du controller (App/Nom_du_Module/Http/Controllers/TestController.php)

    model:
        name: required			# nom du model
        pk: optional			# clé primaire (pour base de données MYSQL)
        table: optional			# nom de la table (pour base de données MYSQL)
        path: optional			# sous-dossier du model (App/Models/Sous_dossier/Test.php)			
        module: optional		# (pour HMVC) nom du module du model (App/Nom_du_Module/Models/Test.php)

    middleware: 
        name: required			# nom du middleware (suffixer par Middleware)
        path: optional			# sous-dossier du middleware (App/Http/Middlewares/Sous_dossier/TestMiddleware.php)
        route: optional			# (pas de value : --route) si saisie, le middleware devient une RouteMiddleware et est enregistré dans le MiddlewareServiceProdiver avec un alias (test => TestMiddleware)
        module: optional		# (pour HMVC) nom du module du middleware (App/Nom_du_Module/Http/Middlewares/TestMiddleware.php)

    voter: 
        name: required			# nom du voter (suffixer par Voter)
        model: required			# nom du model sujet (namespace\\model)
        path: optional			# sous-dossier du middleware (App/Voters/Sous_dossier/TestVoter.php)
        action: optional		# implémente une méthode avec le nom de l'action (private function nom_action(...){})
        module: optional		# (pour HMVC) nom du module du middleware (App/Nom_du_Module/Http/Middlewares/TestMiddleware.php)

    notification: 
        name: required			# nom de la classe notification (suffixer par Notification)
        via: required			# implémente, avec le nom du paramètre, une méthode de connexion entre la classe et le système de notification
        path: optional			# sous-dossier de la notification (App/Notifications/Sous_dossier/TestVoter.php)

    event: 
        name: required			# nom de l'event (suffixer par Event)
        path: optional			# sous-dossier de l'event (App/Voters/Sous_dossier/TestEvent.php)
        module: optional		# (pour HMVC) nom du module de l'event (App/Nom_du_Module/Events/TestEvent.php)

    listener: 
        name: required			# nom du listener (suffixer par Listener)
        path: optional			# sous-dossier du listener (App/Listeners/Sous_dossier/TestListener.php)
        module: optional		# (pour HMVC) nom du module de l'event (App/Nom_du_Module/Listeners/TestListener.php)

    formatter:
        name: required			# nom du voter (suffixer par Formatter)
        format: required		# implémente une méthode avec le nom du format pour les views .mky
        path: optional			# sous-dossier du formatter (App/MkyFormatters/Sous_dossier/TestFormatter.php)

    directive: 
        name: required			# nom de la directive (suffixer par Directive)
        fn: required			# implémente deux méthodes avec le nom du format pour les views .mky ([[$this, 'test'],[$this, 'endtest]])
        path: optional			# sous-dossier de la directive (App/MkyDirectives/Sous_dossier/TestDirective.php)

show: 
    routes: # affiche toutes les routes
        request: optional		# filtre les routes selon la request saisie (get, post,...)
        controller: optional    # filtre les routes selon le controller saisie

cache: 
    clear: 
        path: optional			# nettoie tout le cache ou le sous-dossier saisie

    create: 
        path: required			# crée un sous-dossier dans le cache
```

## HMVC

La structure HMVC permet d'organiser l'application en module de MVC ou chaque module (dossier) a sa propre architecture MVC. Pour créer un module en ligne de commande `php micky --create=module --name=Todo`, la commande créera les dossiers et les fichiers directement est inscrira le module dans le ModuleServiceProvider.php: 
```php
[  
    \App\Todo\TodoModule::class  
];
```
Organisation du module: 
```yaml
Events
Http:
    - Controllers
        - Admin:
            - TodoController.php
        - TodoController.php
    - Middlewares
Listeners
Models
Notifications
Providers:
    - MiddlewareServiceProvider.php
    - EventServiceProvider.php
routes:
    - web.yaml
    - admin.yaml
    - functions.php
views:
    - admin:
        - index.mky
    - index.mky
Voters
config.php
TodoModule.php
```
Pour active le mode HMVC, dans le point de config config/app.php mettre `'structure' => 'HMVC'`.
- Config
La configuration du module se trouve dans le fichier app/Nom_du_Module/config.php
```php
return [
    'views' => __DIR__ . '/views',
    'layouts' => __DIR__. '/views/layouts'
    'url_prefix' => '/products'
];
```
La config surchargera le config/module.php en remplaçant les paramètres si celle-ci sont définie, en retirant le paramètre 'layouts' du app/Module/config.php alors les layouts devrons se trouver dans le layouts par défaut saisie dans la config/module.php.

- Application HMVC

Les events et les listeners doivent être inscrit dans le app/Module/Providers/EventServiceProvider.php et les routeMiddlewares et les voters doivent être inscrit dans le app/Module/Providers/MiddlewareServiceProvider.php
