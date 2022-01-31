
# MickyFramework    
  
> *Framework MVC et HMVC par Micky_N version 0.1*  
  
Framework inspirée de Laravel, modulable en structure MVC et/ou HMVC et utilise un moteur de Template Mky.  
  
## Structure  
  
 ### 1. Dossiers  
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

### 2. Point de configuration

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

### 3. Routes

L'application récupère toutes les routes dans le dossier /routes/*.yaml, les routes sont écrites en format .yaml ainsi que les fonctions de route personnalisées dans le fichier functions.php
```yaml
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

### 4. Providers

Les providers sont des enregistrements de classes dans un but définie.
- EventServiceProvider

Ce provider sert à stocker les events et leurs listeners selon leur actions sont forme de tableau, exemple :
```php
\App\Events\CategoryEvent::class => [  
  'update' => \App\Listeners\UpdateCategoryListener::class,
  'otherAction' => \App\Listeners\OtherListener::class,  
];
```

- MiddlewareServiceProvider

Ce provider sert à stocker les middleware de route avec un alias sous forme de tableau, et les voters, exemple :
```php
[  
  'routeMiddlewares' => [  
	  'auth' => \App\Http\Middlewares\AuthMiddleware::class  
  ],  
  
  'voters' => [  
	 \App\Voters\RoleVoter::class,  
  ],  
];
```

- MkyServiceProvider

Ce provider sert à stocker les fonctions et formats personnalisés du moteur de template Mky, exemple : 
```php
[  
  'formatters' => [ 
	  App\MkyFormatters\ArrayFormatters::class
  ],  
  'directives' => [
	  App\MkyDirectives\CountDirective::class  
  ]
];
```

- Provider

Ce provider sert à stocker des classes pour des utilisations spéciaux; exemple, pour le système de notification les classes sont stockées avec des alias :
```php
[  
  'alias' => [  
	  'webPush' => \App\Utils\WebPushNotification::class  
  ],
  OtherClass::class 
];
```

## Application

### 1. Model

### 2. Controller

### 3. Middleware

### 4. Voter

### 5. Event

### 6. Listener

### 7. Notification

### 8. Router

## Vue

### 1. MkyDirective

### 2. Liste des directives 

### 3. MkyFormatter

### 4. Liste des formats

## MkyCommand CLI