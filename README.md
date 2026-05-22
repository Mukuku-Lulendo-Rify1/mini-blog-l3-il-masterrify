# Évaluation — Mini Blog Laravel / Blade

**Module :** Développement Web avec Laravel
**Niveau :** L3 — Informatique et Logiciels
**Dépôt GitHub :** [Dr-Lab1/mini-blog-l3-il](https://github.com/Dr-Lab1/mini-blog-l3-il)

---

## Mise en place du projet

Avant de commencer l'évaluation, effectuez les étapes suivantes dans l'ordre :

```bash
# 1. Cloner le dépôt GitHub
git clone https://github.com/Dr-Lab1/mini-blog-l3-il.git

# 2. Se déplacer dans le répertoire du projet
cd mini-blog-l3-il

# 3. Installer les dépendances PHP
composer install

# 4. Copier le fichier d'environnement
cp .env.example .env

# 5. Générer la clé de l'application
php artisan key:generate
```

> Assurez-vous d'avoir **PHP 8.1+**, **Composer** et **Laravel 10+** installés sur votre machine avant de commencer.

---

## Travail à réaliser

### Question 1 — Layouts Blade (racines des deux parties)

Créez deux fichiers root Blade distincts :

- `resources/views/App.blade.php` → pour la **partie publique** du blog
- `resources/views/Dashboard.blade.php` → pour la **partie dashboard** (administration)

Chaque root doit utiliser les directives `@yield` pour définir les zones dynamiques (au minimum : `title`, `content`). Chaque vue enfant devra utiliser `@extends` pour hériter du bon layout et `@section` / `@endsection` pour injecter son contenu dans les zones correspondantes.

**Questions :**

1. Quelle est la différence entre `@yield('title')` et `@yield('title', 'Valeur par défaut')` ?

réponse : yield('title') affiche uniquement la section si elle existe, tandis que @yield('title', 'Valeur par défaut') affiche une valeur par défaut si aucune section title n’est définie.

2. Pourquoi utilise-t-on `@extends` plutôt que d'inclure le header et le footer manuellement dans chaque fichier de vue ?

réponse : @extends évite la répétition du code (header, footer, navbar, etc.) et facilite la maintenance du projet.

3. Comment s'assure-t-on qu'une vue du dashboard n'étende jamais accidentellement le layout public ?

réponse : On sépare clairement les layouts :
App.blade.php → public
Dashboard.blade.php → dashboard
Puis chaque vue dashboard utilise uniquement : @extends('Dashboard')
---

### Question 2 — Assets & Composants de la partie publique

1. Déplacez le fichier `index.css` dans le dossier `public/css/`.
2. Référencez-le dans vos layouts en utilisant la fonction **`asset()`** de Laravel.
3. Créez deux **composants Blade anonymes** :
   - `resources/views/components/header.blade.php`
   - `resources/views/components/footer.blade.php`
4. Incluez ces composants dans le layout public en utilisant la syntaxe `@include()`.


---

### Question 3 — Assets & Composants du dashboard

1. Déplacez le fichier `Dashboard.css` dans le dossier `public/css/`.
2. Référencez-le dans vos layouts en utilisant la fonction **`asset()`**.
3. Créez deux composants Blade pour le dashboard :
   - `resources/views/components/dashboard/topbar.blade.php`
   - `resources/views/components/dashboard/sidebar.blade.php`
4. Incluez ces composants dans `Dashboard.blade.php`.

**Questions :**

1. Comment rendre la classe `active` d'un lien de la sidebar **dynamique** selon la route courante, en utilisant `request()->routeIs()` ou `Route::currentRouteName()` ?

réponse :<a class="{{ Route::currentRouteName() == 'dashboard.articles' ? 'active' : '' }}">
    Articles
</a>

2. Pourquoi est-il préférable de placer les composants du dashboard dans un sous-dossier `components/dashboard/` plutôt que directement dans `components/` ?
réponse : Parce que cela permet :
une meilleure organisation des composants ;
de séparer clairement le dashboard de la partie publique ;
d’éviter les conflits de noms ;
de faciliter la maintenance du projet.
---

### Question 4 — Création des routes

Dans le fichier `routes/web.php`, déclarez une route nommée pour chacune des vues suivantes :

**Partie publique :**

| URL | Nom de la route | Description |
|---|---|---|
| `/` | `home` | Page d'accueil |
| `/articles` | `articles.index` | Liste des articles |
| `/articles/{slug}` | `articles.show` | Détail d'un article |
| `/categories` | `categories.index` | Liste des catégories |
| `/about` | `about` | Page à propos |

**Questions :**

1. Quelle est la différence entre `Route::get()` et `Route::post()` ? Dans quel cas utilise-t-on l'un plutôt que l'autre ?
réponse :Route::get() sert aux requêtes GET (affichage des pages).
Route::post() sert aux requêtes POST (envoi de formulaires, création de données).

2. Comment déclarer et nommer une route avec la méthode `->name()` ? Pourquoi les noms de routes sont-ils indispensables pour utiliser `route()` dans les vues Blade ?

réponse :Route::get('/about', [MainController::class, 'about'])
    ->name('about');

3. Qu'est-ce qu'un paramètre de route dynamique comme `{id}` ? Comment le récupérer dans le contrôleur ?

réponse : C’est une valeur variable dans l’URL.

4. Que se passe-t-il si deux routes ont la même URL mais des méthodes HTTP différentes (`GET` et `POST`) ?

réponse :GET sert à afficher la page ou le formulaire
POST sert à envoyer les données du formulaire
Il n’y a donc pas de conflit.

---

### Question 5 — Groupement des routes du dashboard

Créez un **groupe de routes** pour toutes les pages du dashboard en utilisant `Route::prefix()` et `->group()`.

Toutes les routes du dashboard doivent :
- Avoir le **préfixe d'URL** `/dashboard`
- Avoir le **préfixe de nom** `dashboard.`
- Pointer vers les méthodes de `DashboardController`

Exemple de routes attendues :

| URL | Nom de la route | Méthode du contrôleur |
|---|---|---|
| `/dashboard` | `dashboard.index` | `index` |
| `/dashboard/articles` | `dashboard.articles` | `articles` |
| `/dashboard/categories` | `dashboard.categories` | `categories` |
| `/dashboard/utilisateurs` | `dashboard.users` | `users` |
| `/dashboard/commentaires` | `dashboard.comments` | `comments` |
| `/dashboard/reglages` | `dashboard.settings` | `settings` |

**Questions :**

1. Quelle est la syntaxe complète pour créer un groupe de routes avec un préfixe d'URL et un préfixe de nom en même temps ?

réponse : Route::prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('index');

        Route::get('/articles', [DashboardController::class, 'articles'])
            ->name('articles');
    });

2. Quelle est la différence entre `Route::prefix()` et `Route::middleware()` dans un groupe de routes ?

réponse :Route::prefix() sert à ajouter un préfixe aux URLs.
Route::middleware() sert à appliquer une protection ou un filtre aux routes.

3. Qu'est-ce que `Route::resource()` ? Pour quelles ressources (articles, catégories, utilisateurs) serait-il pertinent de l'utiliser et quelles routes génère-t-il automatiquement ?

réponse :Route::resource() crée automatiquement toutes les routes CRUD d’une ressource
Routes générées automatiquement :
index: liste
create: formulaire création
store: enregistrer
show: détail
edit: formulaire modification
update: mise à jour
destroy : suppression
---

### Question 6 — Création des contrôleurs

Générez les deux contrôleurs suivants via la commande `php artisan make:controller` :

**`MainController`** — gérera toutes les vues publiques :
- `index()` → vue de la page d'accueil
- `articles()` → vue de la liste des articles
- `article($slug)` → vue du détail d'un article
- `categories()` → vue de la liste des catégories
- `about()` → vue de la page à propos

**`DashboardController`** — gérera toutes les vues du dashboard :
- `index()` → vue principale du dashboard
- `articles()` → vue des articles (admin)
- `categories()` → vue des catégories (admin)
- `users()` → vue des utilisateurs
- `comments()` → vue des commentaires
- `settings()` → vue des réglages

Chaque méthode doit retourner sa vue correspondante avec `return view('...')`.

**Questions :**

1. Quelle est la commande artisan pour générer un contrôleur ? Quelle option ajouter pour générer directement un **contrôleur de ressource** avec toutes les méthodes CRUD ?

réponse : php artisan make:controller 
php artisan make:controller nomducontroleur --resource
2. Quelle est la convention de nommage des méthodes d'un contrôleur de ressource Laravel (`index`, `show`, `create`, `store`, `edit`, `update`, `destroy`) ? À quelle action correspond chacune ?

réponse : index() → afficher la liste
show() → afficher un élément
create() → afficher le formulaire de création
store() → enregistrer les données
edit() → afficher le formulaire de modification
update() → mettre à jour les données
destroy() → supprimer les données

3. Quelle est la différence entre ces trois façons de passer des données à une vue depuis un contrôleur ?
   ```php
   return view('articles', ['posts' => $posts]);
   return view('articles', compact('posts'));
   return view('articles')->with('posts', $posts);
   ```

réponse : return view('articles', ['posts' => $posts]); : utilise un tableau associatif.
return view('articles', compact('posts'));
: version plus courte et pratique.(utilise le tableau aussi).
return view('articles')->with('posts', $posts);
→ Ici, on utilise la méthode with() pour attacher les données à la vue.
Cette méthode rend le code plus fluide et lisible, surtout lorsqu’on ajoute plusieurs données en chaîne
---

### Question 7 — Liens et navigation

Sont concernés (liste non exhaustive) :
- Les liens de la navbar publique (Accueil, Articles, Catégories, À propos)
- Les liens de la sidebar du dashboard (Dashboard, Articles, Catégories, Utilisateurs, Commentaires, Réglages)
- Le lien « Voir le blog » dans la topbar du dashboard
- Le lien « Dashboard / Admin » dans le footer public
- Les liens « Voir tout → » sur la page d'accueil
- Les liens sur les cartes d'articles (qui mènent vers le détail d'un article)
- Le breadcrumb sur la page article
- Le bouton « ↗ Voir le blog » dans le dashboard

---

## 📁 Structure de fichiers attendue

À la fin de l'évaluation, votre projet doit respecter l'arborescence suivante :

```
resources/
└── views/
    ├── app.blade.php       ← Layout partie publique
    ├── dashboard.blade.php ← Layout dashboard
    ├── components/
    │   ├── header.blade.php           ← Header public
    │   ├── footer.blade.php           ← Footer public
    │   └── dashboard/
    │       ├── topbar.blade.php       ← Topbar dashboard
    │       └── sidebar.blade.php      ← Sidebar dashboard
    ├── public/                        ← Vues publiques
    │   ├── index.blade.php
    │   ├── articles.blade.php
    │   ├── article.blade.php
    │   ├── categories.blade.php
    │   └── about.blade.php
    └── dashboard/                     ← Vues dashboard
        ├── index.blade.php
        ├── articles.blade.php
        ├── categories.blade.php
        ├── users.blade.php
        ├── comments.blade.php
        └── settings.blade.php

app/
└── Http/
    └── Controllers/
        ├── MainController.php
        └── DashboardController.php

public/
└── css/
    ├── public.css
    └── dashboard.css

routes/
└── web.php
```

---

## Critères d'évaluation

| Critère | Points |
|---|---|
| Layouts Blade corrects avec `@extends`, `@yield`, `@section` | 3 pts |
| Composants publics (header, footer) fonctionnels avec `asset()` | 3 pts |
| Composants dashboard (topbar, sidebar) fonctionnels avec `asset()` | 3 pts |
| Routes publiques nommées et correctement déclarées | 3 pts |
| Routes dashboard groupées avec préfixe et nommage cohérent | 3 pts |
| Contrôleurs créés avec les bonnes méthodes et retours de vues | 3 pts |
| Liens Blade partout | 4 pts |
| Réponses aux questions théoriques | 8 pts |
| **Total** | **30 pts** |

---

## Consignes générales

- Le travail est **individuel**.
- Soumettez votre travail en poussant votre code sur un dépôt GitHub **personnel** et en partageant le lien.
- Le dépôt doit contenir un fichier `.env.example` à jour mais **jamais** le fichier `.env` lui-même.
- Toute ressemblance de code entre deux rendus entraînera un **zéro** pour les deux parties concernées.
- Les réponses aux questions théoriques sont à rédiger directement dans ce fichier `README.md`, sous chaque question.

**Bonne évaluation !**