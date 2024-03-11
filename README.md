# README

### Initialiser le projet

1) Clonez le projet

```ps
git clone https://github.com/jonas18121/template_project.git
```

2) Renommez le nom du projet `template_project` par le nouveau nom de votre project exemple `new_project` partout ou on trouve le nom `template_project`

3) Supprimez le fichier `.git` et faire un git init

```ps
git init
```

4) Suivant la version de Symfony qu'on beut utiliser, il faudra surement modifier la version de PHP, de composer et de x-debug dans le fichier PHP/Dockerfile

Exemple pour Symfony 7.0 il faut :

- PHP 8.2
- Composer 2.7.1 
- xdebug-3.2.0 

5) Créez un dossier app à la racine du projet, s'il n'existe pas

```ps
mkdir app
```

6) Construire le projet avec `docker-build` qui représente `docker-compose build`

```ps
make docker-build
```

7) Créez et démarrez les contenaires docker (représente `docker-compose up -d`)

```ps
make run
```

8) (Facultative) Pour arreter et supprimer les contenaires docker (représente `docker-compose down`)

```ps
make down
```

### Créer un projet Symfony dans app

1) La commande ci-dessous permet d'entrez dans le contenaire PHP pour être dans le répertoire `/var/www/app` du contenaire PHP

```ps
make exec-cli-app
```

2) Créez le projet Symfony à partir du répertoire `/var/www/app`

```ps
composer create-project symfony/skeleton:"7.0.*" my_project_directory
```

3) A partir de votre `Explorer de dossier`, copiez tout le contenu du dossier `my_project_directory` et collez le contenu à la racine de `/app` puis supprimez `my_project_directory`

Bravo !!! 

Vous pouvez accéder au projet avec les liens ci-dessous

### Access

Access to the projet locally on : http://127.0.0.1:8971/

Access to the project's phpmyadmin locally on : http://127.0.0.1:8080/
- user : root
- password : ''

### Premier commit sur votre le projet que vous avez préalablement créer

```ps
git add .

git commit -m "First commit"

git branch -M master

git remote add https://github.com/your_user12121/your_project_name.git

git remote  -v

git push -u origine master
```

### Creer une base de données

1. Dans le fichier `.env` renseignez 

    - le `user`, 
    - le `mot de passe`, 
    - le `nom de base de données` 
    - et utiliser le `nom de container MYSQL` à la place de `name_container_mysql`

```ps
###> doctrine/doctrine-bundle ###
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
#
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
DATABASE_URL="mysql://name_user:password@name_container_mysql:3306/db_name?serverVersion=8.0.32&charset=utf8mb4"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
# DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
###< doctrine/doctrine-bundle ###
```

2. faite la commande ci-dessous pour creer la base de données

```ps
php bin/console doctrine:database:create
```