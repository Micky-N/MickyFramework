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
   - [Liste des formats](#liste-des-formats)
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

| Dossier | Point de Config| description |
|--|--|--|
| app.php | app_name | nom de l'application |
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
categories:  
    index:  
        path: /categories  
        action: App\Http\Controllers\CategoryController::index  
        method: GET
        middleware: # Optionnel
            - auth
            - can: edit, product
```

| Paramètre | Valeur |
|--|--|
| Nom de la route | categories.index |
| chemin de l'url | /categories |
| action (contrôleur::méthode) | App\Http\Controllers\CategoryController::index |
| méthode de requête HTTP | GET |
| middleware (en option) | RouteMiddlewares et/ou Voters |

Seul les routes situées dans le fichier admin.yaml sont prefix (nom et chemin de l'url) par admin exemple : nom = admin.categories.index et path = /admin/categories.

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

### Providers

Les providers sont des enregistrements de classes dans un but définie.
- EventServiceProvider

Ce provider sert à stocker les events et leurs listeners selon leur actions sont forme de tableau :
```php
[
    \App\Events\CategoryEvent::class => [  
	'update' => \App\Listeners\UpdateCategoryListener::class,
	'otherAction' => \App\Listeners\OtherListener::class,  
    ]
];
```

- MiddlewareServiceProvider

Ce provider sert à stocker les middleware de route avec un alias, et les voters :
```php
[  
    'routeMiddlewares' => [  
  	'test' => \App\Http\Middlewares\TestMiddleware::class  
    ],  
  
    'voters' => [  
 	\App\Voters\TestVoter::class,  
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
 */
 public function hasMany(string $model, string $foreignKey = '');
  
/*
 * Retourne l'enregistrement de la table par la clé primaire
 */
 * public function belongsTo(string $model, string $foreignKey = '');
  
/*
 * Retourne tous les enregistrements de la table par la clé primaire
 * relation ManyToMany
 */
 public function belongsToMany(string $model, string $pivot = '', string $foreignKeyOne = '', string $foreignKeyTwo = '');
  
/*
 * Retourne l'enregistrement de la table par la clé primaire
 * relation OneToOne
 */
 public function hasOne(string $model, string $foreignKey = '');
  
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
* @method static \Core\QueryBuilderMysql where($args) // 
// si 3 arguments, Todo::where('task', '!=', 'Coder') => WHERE task != 'Coder', 
// si 2 arguments, Todo::where('task', 'Coder') => WHERE task = 'Coder'

* @method static \Core\QueryBuilderMysql select($args)
// Todo::select('id', 'task') => SELECT id, task...

* @method static \Core\QueryBuilderMysql from(string $table, $alias = null)
// Todo::from('todolist', 'tdl') => FROM todolist (si $alias non null: AS tdl), utile si la table désiré est différente de la table du model en cours, sinon ne pas utiliser from() car elle est sous-entendu

* @method static \Core\QueryBuilderMysql join(string $join_table, string $on, string $operation, string $to, string $alias = '')
// Todo::join('users', 'user_id', '=', 'id', 'usr') => JOIN users (si $alias non null: AS usr) ON todo.user_id = users.id (ou avec alias usr.id) 

* @method static \Core\QueryBuilderMysql first()
//Todo::first() => ORDER BY id ASC LIMIT 1, recupère le premier enregistrement de la table du model

* @method static \Core\QueryBuilderMysql query(string $statement)
// Todo::query('SELECT ....') => execute un requête

* @method static \Core\QueryBuilderMysql prepare(string $statement, array $attribute)
// Todo::prepare('INSERT INTO ....', [...]) => execute un requête preparé avec le paramètre

* @method static \Core\QueryBuilderMysql orderBy($args)
// Todo::orderBy('id') => ORDER BY id, si orderBy('id', 'DESC') => ORDER BY id DESC

* @method static \Core\QueryBuilderMysql limit($args)
// Todo::limit(1) => LIMIT 1 ou Todo::limit(1,2) => LIMIT 1 OFFSET 2

* @method static \Core\QueryBuilderMysql groupBy($args)
// Todo::groupBy('completed') => GROUP BY completed

* @method static array map(string $key, $value = null)
// Todo('task', $value) => réorganise tous les enregistrements en tableau avec comme clé la valeur $key ('task')
// si $value == null alors $value = tous les champs de l'enregistrement
// si $value == type string ex: 'user_id' alors le tableau sera composé de task => user_id (Coder => USR001)
// si $value == type array ex: [user_id, completed] alors 'Coder' => [USR001, true]

* @method static array get() // récupère les enregistrement suivant la requête passé
// Par défaut Todo::get() => SELECT * FROM todolist
// Possible de chainer les methodes pour finir avec get()
// ex: Todo::select('task', 'username')->join('users', 'user_id', 'id')->where('completed', true)->get()

* @method static array toArray()
// Transforme l'instance en tableau associatif

* @method static \Core\Model|bool last()
// Todo::last() => Récupère le dernier enregistrement de la table en cours

* @method static string stringify()
// Todo::select('task', 'created_todo_at')->where('completed', true)->stringify()
// retourne la requête en string 'SELECT task, created_todo_at FROM todolist WHERE completed = 1'
```

### Controller

### Middleware

### Voter

### Event

### Listener

### Notification

### Router

## Vue

Les vues sont prefixées par l'extension .mky (index.mky)

### MkyDirective

### Liste des directives 

### MkyFormatter

### Liste des formats

## MkyCommand CLI

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
