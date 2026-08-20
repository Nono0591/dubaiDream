# Dictionnaire des données – Site e-commerce Dubai Dream

## 1. Présentation

Ce document présente le dictionnaire des données du site e-commerce **Dubai Dream**.
Il décrit les principales données utilisées par l'application ainsi que leurs types, leurs clés et leurs relations. Le modèle de données est basé sur les entités Doctrine du projet Symfony.
Le système permet notamment de gérer les utilisateurs, leurs adresses, les produits, les catégories, les marques, les commandes, les moyens de livraison, les avis clients ainsi que le contenu éditorial du site.
Certaines données sont volontairement dupliquées dans les commandes afin de conserver un historique fiable des informations au moment de l'achat.

---

# 2. Table : User

## Description

Cette table stocke les informations relatives aux utilisateurs du site, notamment les clients et les administrateurs.

| Attribut  | Type         | Clé                | Description                           |
| --------- | ------------ | ------------------ | ------------------------------------- | -------------------------------------------------------- |
| id        | INT          | PK, AUTO_INCREMENT | Identifiant unique de l'utilisateur   |
| email     | VARCHAR(180) | UNIQUE             | Adresse électronique de l'utilisateur |
| roles     | JSON         |                    | Rôles associés à l'utilisateur        |
| password  | VARCHAR(255) |                    | Mot de passe chiffré                  |
| firstname | VARCHAR(50)  |                    | Prénom de l'utilisateur               | # Dictionnaire des données – Site e-commerce Dubai Dream |

## 1. Présentation

Ce document présente le dictionnaire des données du site e-commerce **Dubai Dream**.
Il décrit les principales données utilisées par l'application ainsi que leurs types, leurs clés et leurs relations. Le modèle de données est basé sur les entités Doctrine du projet Symfony.
Le système permet notamment de gérer les utilisateurs, leurs adresses, les produits, les catégories, les marques, les commandes, les moyens de livraison, les avis clients ainsi que le contenu éditorial du site.
Certaines données sont volontairement dupliquées dans les commandes afin de conserver un historique fiable des informations au moment de l'achat.

---

# 2. Table : User

## Description

Cette table stocke les informations relatives aux utilisateurs du site, notamment les clients et les administrateurs.

| Attribut  | Type         | Clé                | Description                           |
| --------- | ------------ | ------------------ | ------------------------------------- |
| id        | INT          | PK, AUTO_INCREMENT | Identifiant unique de l'utilisateur   |
| email     | VARCHAR(180) | UNIQUE             | Adresse électronique de l'utilisateur |
| roles     | JSON         |                    | Rôles associés à l'utilisateur        |
| password  | VARCHAR(255) |                    | Mot de passe chiffré                  |
| firstname | VARCHAR(50)  |                    | Prénom de l'utilisateur               |
| lastname  | VARCHAR(50)  |                    | Nom de l'utilisateur                  |

| lastLoginAt | DATETIME | | Date de dernière connexion |

---

# 3. Table : Address

## Description

Cette table contient les adresses associées aux utilisateurs.
Un utilisateur peut posséder plusieurs adresses.

| Attribut  | Type         | Clé                | Description                           |
| --------- | ------------ | ------------------ | ------------------------------------- |
| id        | INT          | PK, AUTO_INCREMENT | Identifiant unique de l'adresse       |
| firstname | VARCHAR(255) |                    | Prénom du destinataire                |
| lastname  | VARCHAR(255) |                    | Nom du destinataire                   |
| address   | VARCHAR(255) |                    | Adresse postale                       |
| postcode  | VARCHAR(255) |                    | Code postal                           |
| city      | VARCHAR(255) |                    | Ville                                 |
| country   | VARCHAR(255) |                    | Pays                                  |
| phone     | VARCHAR(255) |                    | Numéro de téléphone                   |
| user_id   | INT          | FK                 | Utilisateur propriétaire de l'adresse |

**Relation :**

`User 1,N Address`

---

# 4. Table : Category

## Description

Cette table permet de classer les produits du catalogue par catégories.

| Attribut     | Type         | Clé                | Description                                     |
| ------------ | ------------ | ------------------ | ----------------------------------------------- |
| id           | INT          | PK, AUTO_INCREMENT | Identifiant unique de la catégorie              |
| name         | VARCHAR(255) |                    | Nom de la catégorie                             |
| slug         | VARCHAR(255) |                    | Version simplifiée du nom utilisée dans les URL |
| description  | TEXT         |                    | Description de la catégorie                     |
| illustration | VARCHAR(255) |                    | Nom ou chemin de l'image de la catégorie        |

Un produit peut appartenir à une catégorie.

**Relation :**

`Category 1,N Product`

---

# 5. Table : Brand

## Description

Cette table contient les marques associées aux produits.

| Attribut | Type         | Clé                | Description                     |
| -------- | ------------ | ------------------ | ------------------------------- |
| id       | INT          | PK, AUTO_INCREMENT | Identifiant unique de la marque |
| name     | VARCHAR(255) |                    | Nom de la marque                |
| logo     | VARCHAR(255) |                    | Logo de la marque               |

Une marque peut être associée à plusieurs produits.

**Relation :**

`Brand 1,N Product`

---

# 6. Table : Product

## Description

Cette table constitue le catalogue des produits commercialisés sur le site.
Les produits peuvent correspondre à différentes catégories : parfumerie, produits d'entretien, hygiène, etc.

| Attribut     | Type          | Clé                | Description                                                  |
| ------------ | ------------- | ------------------ | ------------------------------------------------------------ |
| id           | INT           | PK, AUTO_INCREMENT | Identifiant unique du produit                                |
| name         | VARCHAR(255)  |                    | Nom du produit                                               |
| slug         | VARCHAR(255)  |                    | Version simplifiée du nom utilisée dans les URL              |
| description  | TEXT          |                    | Description du produit                                       |
| illustration | VARCHAR(255)  |                    | Image ou illustration du produit                             |
| price        | DECIMAL(10,2) |                    | Prix hors taxes du produit                                   |
| tva          | DECIMAL(5,2)  |                    | Taux de TVA appliqué                                         |
| isHomepage   | BOOLEAN       |                    | Indique si le produit est mis en avant sur la page d'accueil |
| category_id  | INT           | FK, NULL           | Catégorie associée au produit                                |
| brand_id     | INT           | FK, NULL           | Marque associée au produit                                   |

**Relations :**

`Category 1,N Product`

`Brand 1,N Product`

Les relations vers `Category` et `Brand` sont facultatives.

---

# 7. Table : Order

## Description

Cette table contient les informations générales relatives aux commandes effectuées par les utilisateurs.
Le transporteur n'est pas conservé sous forme de clé étrangère. Son nom et son prix sont copiés directement dans la commande au moment de sa création.
Cette duplication permet de conserver l'historique de la commande même si les informations du transporteur sont modifiées ultérieurement.

| Attribut          | Type          | Clé                | Description                                           |
| ----------------- | ------------- | ------------------ | ----------------------------------------------------- |
| id                | INT           | PK, AUTO_INCREMENT | Identifiant unique de la commande                     |
| createdAt         | DATETIME      |                    | Date de création de la commande                       |
| state             | INT           |                    | État actuel de la commande                            |
| carrierName       | VARCHAR(255)  |                    | Nom du transporteur au moment de la commande          |
| carrierPrice      | DECIMAL(10,2) |                    | Prix de livraison au moment de la commande            |
| delivery          | TEXT          |                    | Adresse de livraison enregistrée pour la commande     |
| stripe_session_id | VARCHAR/TEXT  |                    | Identifiant de la session Stripe associée au paiement |
| user_id           | INT           | FK                 | Utilisateur ayant passé la commande                   |

### États de commande

| Valeur | Signification          |
| -----: | ---------------------- |
|      1 | En attente de paiement |
|      2 | Paiement accepté       |
|      3 | En cours de traitement |
|      4 | Expédiée               |
|      5 | Annulée                |

**Relation :**

`User 1,N Order`

---

# 8. Table : OrderDetail

## Description

Cette table représente les différentes lignes d'une commande.
Les informations principales du produit sont volontairement copiées au moment de l'achat.
Cette méthode permet de conserver l'état historique du produit même si son nom, son image, son prix ou son taux de TVA sont modifiés ultérieurement.

| Attribut            | Type          | Clé                | Description                                  |
| ------------------- | ------------- | ------------------ | -------------------------------------------- |
| id                  | INT           | PK, AUTO_INCREMENT | Identifiant unique de la ligne               |
| productName         | VARCHAR(255)  |                    | Nom du produit au moment de l'achat          |
| productIllustration | VARCHAR(255)  |                    | Illustration du produit au moment de l'achat |
| productQuantity     | INT           |                    | Quantité commandée                           |
| productPrice        | DECIMAL(10,2) |                    | Prix unitaire HT au moment de l'achat        |
| productTva          | DECIMAL(5,2)  |                    | Taux de TVA appliqué au moment de l'achat    |
| order_id            | INT           | FK                 | Commande associée                            |

**Relation :**

`Order 1,N OrderDetail`

### Remarque

Il n'existe volontairement pas de clé étrangère directe vers `Product`.
Les informations nécessaires à l'historique de la commande sont copiées dans `OrderDetail`.

---

# 9. Table : Carrier

## Description

Cette table contient les transporteurs disponibles pour la livraison des commandes.
Les transporteurs sont indépendants des commandes : lorsqu'une commande est créée, le nom et le prix du transporteur sont copiés dans la table `Order`.

| Attribut    | Type          | Clé                | Description                         |
| ----------- | ------------- | ------------------ | ----------------------------------- |
| id          | INT           | PK, AUTO_INCREMENT | Identifiant unique du transporteur  |
| name        | VARCHAR(50)   |                    | Nom du transporteur                 |
| description | TEXT          |                    | Description du service de livraison |
| price       | DECIMAL(10,2) |                    | Prix de livraison                   |

**Remarque :**

La table `Carrier` ne possède pas de relation directe avec `Order`.

---

# 10. Table : Review

## Description

Cette table contient les avis clients affichés sur le site.
Le champ `product` contient le nom du produit sous forme de texte. Il ne s'agit pas d'une relation avec la table `Product`.

| Attribut     | Type         | Clé                | Description                                |
| ------------ | ------------ | ------------------ | ------------------------------------------ |
| id           | INT          | PK, AUTO_INCREMENT | Identifiant unique de l'avis               |
| name         | VARCHAR(255) |                    | Nom de l'auteur de l'avis                  |
| badge        | VARCHAR(255) | NULL               | Badge affiché pour l'auteur                |
| reviewsCount | INT          | NULL               | Nombre d'avis de l'auteur                  |
| photosCount  | INT          | NULL               | Nombre de photos associées                 |
| text         | TEXT         |                    | Contenu de l'avis                          |
| rating       | INT          |                    | Note attribuée                             |
| date         | VARCHAR(255) | NULL               | Date de publication de l'avis              |
| visitDate    | VARCHAR(255) | NULL               | Date de visite ou d'achat                  |
| avatar       | VARCHAR(10)  | NULL               | Initiales ou identifiant court de l'avatar |
| product      | VARCHAR(255) | NULL               | Nom du produit concerné                    |
| isVisible    | BOOLEAN      |                    | Indique si l'avis est visible              |
| position     | INT          |                    | Ordre d'affichage de l'avis                |

**Remarque :**

Le champ `product` est volontairement stocké comme texte et ne possède pas de clé étrangère vers `Product`.

---

# 11. Table : Header

## Description

Cette table contient les éléments éditoriaux affichés dans le bandeau principal de la page d'accueil.
Elle permet notamment de gérer les différents éléments du slider principal.

| Attribut     | Type         | Clé                | Description                   |
| ------------ | ------------ | ------------------ | ----------------------------- |
| id           | INT          | PK, AUTO_INCREMENT | Identifiant unique du contenu |
| title        | VARCHAR(255) |                    | Titre principal               |
| content      | TEXT         |                    | Texte de présentation         |
| buttonTitle  | VARCHAR(255) |                    | Texte du bouton d'action      |
| illustration | VARCHAR(255) |                    | Image du bandeau              |
| buttonLink   | VARCHAR(255) |                    | Destination du bouton         |

Cette table est indépendante des autres tables du modèle.

---

# 12. Table : Wishlist

## Description

L'entité `Wishlist` représente une liste de souhaits indépendante pouvant être associée à plusieurs produits.

| Attribut | Type | Clé                | Description                       |
| -------- | ---- | ------------------ | --------------------------------- |
| id       | INT  | PK, AUTO_INCREMENT | Identifiant unique de la wishlist |

Cette entité possède une relation plusieurs-à-plusieurs avec `Product`.
Cependant, elle n'est pas directement rattachée à `User`.

---

# 13. Table de liaison : user_wishlist

## Description

Cette table représente le mécanisme de liste de souhaits associé directement aux utilisateurs.
Elle permet à un utilisateur d'ajouter plusieurs produits à sa wishlist et à un produit d'être présent dans les wishlists de plusieurs utilisateurs.

| Attribut   | Type | Clé    | Description                  |
| ---------- | ---- | ------ | ---------------------------- |
| user_id    | INT  | PK, FK | Identifiant de l'utilisateur |
| product_id | INT  | PK, FK | Identifiant du produit       |

### Relation

`User N,N Product`

La table `user_wishlist` est donc une table de liaison permettant de gérer la wishlist personnelle des utilisateurs.

---

# 14. Clés primaires

| Table         | Clé primaire         |
| ------------- | -------------------- |
| User          | id                   |
| Address       | id                   |
| Category      | id                   |
| Brand         | id                   |
| Product       | id                   |
| Order         | id                   |
| OrderDetail   | id                   |
| Carrier       | id                   |
| Review        | id                   |
| Header        | id                   |
| Wishlist      | id                   |
| user_wishlist | user_id + product_id |

---

# 15. Clés étrangères

| Table         | Clé étrangère | Référence    | Obligatoire |
| ------------- | ------------- | ------------ | ----------- |
| Address       | user_id       | User(id)     | Oui         |
| Order         | user_id       | User(id)     | Oui         |
| OrderDetail   | order_id      | Order(id)    | Oui         |
| Product       | category_id   | Category(id) | Non         |
| Product       | brand_id      | Brand(id)    | Non         |
| user_wishlist | user_id       | User(id)     | Oui         |
| user_wishlist | product_id    | Product(id)  | Oui         |

---

# 16. Relations entre les tables

| Relation            | Cardinalité | Description                                        |
| ------------------- | ----------- | -------------------------------------------------- |
| User → Address      | 1,N         | Un utilisateur peut avoir plusieurs adresses       |
| User → Order        | 1,N         | Un utilisateur peut passer plusieurs commandes     |
| Order → OrderDetail | 1,N         | Une commande contient plusieurs lignes             |
| Category → Product  | 1,N         | Une catégorie peut contenir plusieurs produits     |
| Brand → Product     | 1,N         | Une marque peut être associée à plusieurs produits |
| User ↔ Product      | N,N         | Gestion de la wishlist utilisateur                 |
| Wishlist ↔ Product  | N,N         | Relation de la wishlist indépendante               |

---

# 17. Absence volontaire de certaines relations

Certaines informations ne possèdent volontairement pas de clé étrangère.

### Order → Carrier

La commande ne possède pas de clé étrangère vers `Carrier`.
Le nom et le prix du transporteur sont copiés dans `Order` lors de la création de la commande.
Cela permet de conserver l'historique du tarif appliqué.

### OrderDetail → Product

Une ligne de commande ne possède pas de clé étrangère vers `Product`.
Les informations nécessaires à l'historique sont copiées directement dans `OrderDetail`.
Cela permet de conserver le nom, l'image, le prix et la TVA appliqués lors de l'achat.

### Review → Product

Le champ `product` de `Review` est enregistré comme texte libre.
Il n'existe donc pas de relation directe avec `Product`.

### Wishlist → User

L'entité `Wishlist` indépendante n'est pas directement reliée à `User`.
La wishlist utilisateur est gérée par la relation `User ↔ Product` via la table `user_wishlist`.

---

# 18. Synthèse du modèle de données

Le modèle de données du site Dubai Dream permet de gérer l'ensemble du fonctionnement d'une boutique en ligne.
Il permet notamment :

- la gestion des utilisateurs et de leurs rôles ;
- la gestion des adresses de livraison ;
- la gestion des catégories ;
- la gestion des marques ;
- la gestion du catalogue de produits ;
- la gestion des wishlists ;
- la gestion des transporteurs ;
- la gestion des commandes ;
- la gestion du détail des commandes ;
- la conservation de l'historique des produits commandés ;
- la gestion des avis clients ;
- la gestion du contenu éditorial de la page d'accueil.

La structure utilise également une **dénormalisation volontaire des données de commande**. Les informations importantes du produit et du transporteur sont copiées au moment de l'achat afin de garantir la conservation de l'historique, même lorsque les données originales sont modifiées par la suite.

---

# 19. Améliorations techniques recommandées

Le dictionnaire ci-dessus présente les types recommandés pour une conception propre de la base de données.
Deux améliorations sont particulièrement importantes :

### Gestion des quantités

Le champ `productQuantity` doit être de type :

`INT`

car il représente une quantité numérique et intervient dans les calculs du montant de la commande.

### Gestion des montants

Les champs représentant des montants financiers devraient idéalement utiliser :

`DECIMAL(10,2)`

plutôt que `FLOAT`.

Cela concerne notamment :

- `Product.price`
- `Product.tva`
- `Carrier.price`
- `Order.carrierPrice`
- `OrderDetail.productPrice`
- `OrderDetail.productTva`

L'utilisation de `DECIMAL` permet d'éviter les problèmes de précision liés aux nombres flottants lors des calculs financiers.

---

# 20. Conclusion

Le modèle de données de Dubai Dream repose sur des relations classiques entre les utilisateurs, les produits, les catégories, les marques et les commandes.
Les choix de duplication des données dans `Order` et `OrderDetail` sont volontaires et permettent de préserver l'historique des commandes.
Le modèle comporte également deux mécanismes de wishlist : la relation directe entre `User` et `Product` via `user_wishlist`, utilisée pour la wishlist personnelle, et l'entité `Wishlist` indépendante.
Cette structure répond aux besoins fonctionnels du site tout en laissant la possibilité d'améliorer certains choix techniques, notamment le stockage des quantités et des montants financiers.

| lastname | VARCHAR(50) | | Nom de l'utilisateur | |
| lastLoginAt | DATETIME | | Date de dernière connexion |

---

# 3. Table : Address

## Description

Cette table contient les adresses associées aux utilisateurs.
Un utilisateur peut posséder plusieurs adresses.

| Attribut  | Type         | Clé                | Description                           |
| --------- | ------------ | ------------------ | ------------------------------------- |
| id        | INT          | PK, AUTO_INCREMENT | Identifiant unique de l'adresse       |
| firstname | VARCHAR(255) |                    | Prénom du destinataire                |
| lastname  | VARCHAR(255) |                    | Nom du destinataire                   |
| address   | VARCHAR(255) |                    | Adresse postale                       |
| postcode  | VARCHAR(255) |                    | Code postal                           |
| city      | VARCHAR(255) |                    | Ville                                 |
| country   | VARCHAR(255) |                    | Pays                                  |
| phone     | VARCHAR(255) |                    | Numéro de téléphone                   |
| user_id   | INT          | FK                 | Utilisateur propriétaire de l'adresse |

**Relation :**

`User 1,N Address`

---

# 4. Table : Category

## Description

Cette table permet de classer les produits du catalogue par catégories.

| Attribut     | Type         | Clé                | Description                                     |
| ------------ | ------------ | ------------------ | ----------------------------------------------- |
| id           | INT          | PK, AUTO_INCREMENT | Identifiant unique de la catégorie              |
| name         | VARCHAR(255) |                    | Nom de la catégorie                             |
| slug         | VARCHAR(255) |                    | Version simplifiée du nom utilisée dans les URL |
| description  | TEXT         |                    | Description de la catégorie                     |
| illustration | VARCHAR(255) |                    | Nom ou chemin de l'image de la catégorie        |

Un produit peut appartenir à une catégorie.

**Relation :**

`Category 1,N Product`

---

# 5. Table : Brand

## Description

Cette table contient les marques associées aux produits.

| Attribut | Type         | Clé                | Description                     |
| -------- | ------------ | ------------------ | ------------------------------- |
| id       | INT          | PK, AUTO_INCREMENT | Identifiant unique de la marque |
| name     | VARCHAR(255) |                    | Nom de la marque                |
| logo     | VARCHAR(255) |                    | Logo de la marque               |

Une marque peut être associée à plusieurs produits.

**Relation :**

`Brand 1,N Product`

---

# 6. Table : Product

## Description

Cette table constitue le catalogue des produits commercialisés sur le site.
Les produits peuvent correspondre à différentes catégories : parfumerie, produits d'entretien, hygiène, etc.

| Attribut     | Type          | Clé                | Description                                                  |
| ------------ | ------------- | ------------------ | ------------------------------------------------------------ |
| id           | INT           | PK, AUTO_INCREMENT | Identifiant unique du produit                                |
| name         | VARCHAR(255)  |                    | Nom du produit                                               |
| slug         | VARCHAR(255)  |                    | Version simplifiée du nom utilisée dans les URL              |
| description  | TEXT          |                    | Description du produit                                       |
| illustration | VARCHAR(255)  |                    | Image ou illustration du produit                             |
| price        | DECIMAL(10,2) |                    | Prix hors taxes du produit                                   |
| tva          | DECIMAL(5,2)  |                    | Taux de TVA appliqué                                         |
| isHomepage   | BOOLEAN       |                    | Indique si le produit est mis en avant sur la page d'accueil |
| category_id  | INT           | FK, NULL           | Catégorie associée au produit                                |
| brand_id     | INT           | FK, NULL           | Marque associée au produit                                   |

**Relations :**

`Category 1,N Product`

`Brand 1,N Product`

Les relations vers `Category` et `Brand` sont facultatives.

---

# 7. Table : Order

## Description

Cette table contient les informations générales relatives aux commandes effectuées par les utilisateurs.
Le transporteur n'est pas conservé sous forme de clé étrangère. Son nom et son prix sont copiés directement dans la commande au moment de sa création.
Cette duplication permet de conserver l'historique de la commande même si les informations du transporteur sont modifiées ultérieurement.

| Attribut          | Type          | Clé                | Description                                           |
| ----------------- | ------------- | ------------------ | ----------------------------------------------------- |
| id                | INT           | PK, AUTO_INCREMENT | Identifiant unique de la commande                     |
| createdAt         | DATETIME      |                    | Date de création de la commande                       |
| state             | INT           |                    | État actuel de la commande                            |
| carrierName       | VARCHAR(255)  |                    | Nom du transporteur au moment de la commande          |
| carrierPrice      | DECIMAL(10,2) |                    | Prix de livraison au moment de la commande            |
| delivery          | TEXT          |                    | Adresse de livraison enregistrée pour la commande     |
| stripe_session_id | VARCHAR/TEXT  |                    | Identifiant de la session Stripe associée au paiement |
| user_id           | INT           | FK                 | Utilisateur ayant passé la commande                   |

### États de commande

| Valeur | Signification          |
| -----: | ---------------------- |
|      1 | En attente de paiement |
|      2 | Paiement accepté       |
|      3 | En cours de traitement |
|      4 | Expédiée               |
|      5 | Annulée                |

**Relation :**

`User 1,N Order`

---

# 8. Table : OrderDetail

## Description

Cette table représente les différentes lignes d'une commande.
Les informations principales du produit sont volontairement copiées au moment de l'achat.
Cette méthode permet de conserver l'état historique du produit même si son nom, son image, son prix ou son taux de TVA sont modifiés ultérieurement.

| Attribut            | Type          | Clé                | Description                                  |
| ------------------- | ------------- | ------------------ | -------------------------------------------- |
| id                  | INT           | PK, AUTO_INCREMENT | Identifiant unique de la ligne               |
| productName         | VARCHAR(255)  |                    | Nom du produit au moment de l'achat          |
| productIllustration | VARCHAR(255)  |                    | Illustration du produit au moment de l'achat |
| productQuantity     | INT           |                    | Quantité commandée                           |
| productPrice        | DECIMAL(10,2) |                    | Prix unitaire HT au moment de l'achat        |
| productTva          | DECIMAL(5,2)  |                    | Taux de TVA appliqué au moment de l'achat    |
| order_id            | INT           | FK                 | Commande associée                            |

**Relation :**

`Order 1,N OrderDetail`

### Remarque

Il n'existe volontairement pas de clé étrangère directe vers `Product`.
Les informations nécessaires à l'historique de la commande sont copiées dans `OrderDetail`.

---

# 9. Table : Carrier

## Description

Cette table contient les transporteurs disponibles pour la livraison des commandes.
Les transporteurs sont indépendants des commandes : lorsqu'une commande est créée, le nom et le prix du transporteur sont copiés dans la table `Order`.

| Attribut    | Type          | Clé                | Description                         |
| ----------- | ------------- | ------------------ | ----------------------------------- |
| id          | INT           | PK, AUTO_INCREMENT | Identifiant unique du transporteur  |
| name        | VARCHAR(50)   |                    | Nom du transporteur                 |
| description | TEXT          |                    | Description du service de livraison |
| price       | DECIMAL(10,2) |                    | Prix de livraison                   |

**Remarque :**

La table `Carrier` ne possède pas de relation directe avec `Order`.

---

# 10. Table : Review

## Description

Cette table contient les avis clients affichés sur le site.
Le champ `product` contient le nom du produit sous forme de texte. Il ne s'agit pas d'une relation avec la table `Product`.

| Attribut     | Type         | Clé                | Description                                |
| ------------ | ------------ | ------------------ | ------------------------------------------ |
| id           | INT          | PK, AUTO_INCREMENT | Identifiant unique de l'avis               |
| name         | VARCHAR(255) |                    | Nom de l'auteur de l'avis                  |
| badge        | VARCHAR(255) | NULL               | Badge affiché pour l'auteur                |
| reviewsCount | INT          | NULL               | Nombre d'avis de l'auteur                  |
| photosCount  | INT          | NULL               | Nombre de photos associées                 |
| text         | TEXT         |                    | Contenu de l'avis                          |
| rating       | INT          |                    | Note attribuée                             |
| date         | VARCHAR(255) | NULL               | Date de publication de l'avis              |
| visitDate    | VARCHAR(255) | NULL               | Date de visite ou d'achat                  |
| avatar       | VARCHAR(10)  | NULL               | Initiales ou identifiant court de l'avatar |
| product      | VARCHAR(255) | NULL               | Nom du produit concerné                    |
| isVisible    | BOOLEAN      |                    | Indique si l'avis est visible              |
| position     | INT          |                    | Ordre d'affichage de l'avis                |

**Remarque :**

Le champ `product` est volontairement stocké comme texte et ne possède pas de clé étrangère vers `Product`.

---

# 11. Table : Header

## Description

Cette table contient les éléments éditoriaux affichés dans le bandeau principal de la page d'accueil.
Elle permet notamment de gérer les différents éléments du slider principal.

| Attribut     | Type         | Clé                | Description                   |
| ------------ | ------------ | ------------------ | ----------------------------- |
| id           | INT          | PK, AUTO_INCREMENT | Identifiant unique du contenu |
| title        | VARCHAR(255) |                    | Titre principal               |
| content      | TEXT         |                    | Texte de présentation         |
| buttonTitle  | VARCHAR(255) |                    | Texte du bouton d'action      |
| illustration | VARCHAR(255) |                    | Image du bandeau              |
| buttonLink   | VARCHAR(255) |                    | Destination du bouton         |

Cette table est indépendante des autres tables du modèle.

---

# 12. Table : Wishlist

## Description

L'entité `Wishlist` représente une liste de souhaits indépendante pouvant être associée à plusieurs produits.

| Attribut | Type | Clé                | Description                       |
| -------- | ---- | ------------------ | --------------------------------- |
| id       | INT  | PK, AUTO_INCREMENT | Identifiant unique de la wishlist |

Cette entité possède une relation plusieurs-à-plusieurs avec `Product`.
Cependant, elle n'est pas directement rattachée à `User`.

---

# 13. Table de liaison : user_wishlist

## Description

Cette table représente le mécanisme de liste de souhaits associé directement aux utilisateurs.
Elle permet à un utilisateur d'ajouter plusieurs produits à sa wishlist et à un produit d'être présent dans les wishlists de plusieurs utilisateurs.

| Attribut   | Type | Clé    | Description                  |
| ---------- | ---- | ------ | ---------------------------- |
| user_id    | INT  | PK, FK | Identifiant de l'utilisateur |
| product_id | INT  | PK, FK | Identifiant du produit       |

### Relation

`User N,N Product`

La table `user_wishlist` est donc une table de liaison permettant de gérer la wishlist personnelle des utilisateurs.

---

# 14. Clés primaires

| Table         | Clé primaire         |
| ------------- | -------------------- |
| User          | id                   |
| Address       | id                   |
| Category      | id                   |
| Brand         | id                   |
| Product       | id                   |
| Order         | id                   |
| OrderDetail   | id                   |
| Carrier       | id                   |
| Review        | id                   |
| Header        | id                   |
| Wishlist      | id                   |
| user_wishlist | user_id + product_id |

---

# 15. Clés étrangères

| Table         | Clé étrangère | Référence    | Obligatoire |
| ------------- | ------------- | ------------ | ----------- |
| Address       | user_id       | User(id)     | Oui         |
| Order         | user_id       | User(id)     | Oui         |
| OrderDetail   | order_id      | Order(id)    | Oui         |
| Product       | category_id   | Category(id) | Non         |
| Product       | brand_id      | Brand(id)    | Non         |
| user_wishlist | user_id       | User(id)     | Oui         |
| user_wishlist | product_id    | Product(id)  | Oui         |

---

# 16. Relations entre les tables

| Relation            | Cardinalité | Description                                        |
| ------------------- | ----------- | -------------------------------------------------- |
| User → Address      | 1,N         | Un utilisateur peut avoir plusieurs adresses       |
| User → Order        | 1,N         | Un utilisateur peut passer plusieurs commandes     |
| Order → OrderDetail | 1,N         | Une commande contient plusieurs lignes             |
| Category → Product  | 1,N         | Une catégorie peut contenir plusieurs produits     |
| Brand → Product     | 1,N         | Une marque peut être associée à plusieurs produits |
| User ↔ Product      | N,N         | Gestion de la wishlist utilisateur                 |
| Wishlist ↔ Product  | N,N         | Relation de la wishlist indépendante               |

---

# 17. Absence volontaire de certaines relations

Certaines informations ne possèdent volontairement pas de clé étrangère.

### Order → Carrier

La commande ne possède pas de clé étrangère vers `Carrier`.
Le nom et le prix du transporteur sont copiés dans `Order` lors de la création de la commande.
Cela permet de conserver l'historique du tarif appliqué.

### OrderDetail → Product

Une ligne de commande ne possède pas de clé étrangère vers `Product`.
Les informations nécessaires à l'historique sont copiées directement dans `OrderDetail`.
Cela permet de conserver le nom, l'image, le prix et la TVA appliqués lors de l'achat.

### Review → Product

Le champ `product` de `Review` est enregistré comme texte libre.

Il n'existe donc pas de relation directe avec `Product`.

### Wishlist → User

L'entité `Wishlist` indépendante n'est pas directement reliée à `User`.
La wishlist utilisateur est gérée par la relation `User ↔ Product` via la table `user_wishlist`.

---

# 18. Synthèse du modèle de données

Le modèle de données du site Dubai Dream permet de gérer l'ensemble du fonctionnement d'une boutique en ligne.

Il permet notamment :

- la gestion des utilisateurs et de leurs rôles ;
- la gestion des adresses de livraison ;
- la gestion des catégories ;
- la gestion des marques ;
- la gestion du catalogue de produits ;
- la gestion des wishlists ;
- la gestion des transporteurs ;
- la gestion des commandes ;
- la gestion du détail des commandes ;
- la conservation de l'historique des produits commandés ;
- la gestion des avis clients ;
- la gestion du contenu éditorial de la page d'accueil.

La structure utilise également une **dénormalisation volontaire des données de commande**. Les informations importantes du produit et du transporteur sont copiées au moment de l'achat afin de garantir la conservation de l'historique, même lorsque les données originales sont modifiées par la suite.

---

# 19. Conclusion

Le modèle de données de Dubai Dream repose sur des relations classiques entre les utilisateurs, les produits, les catégories, les marques et les commandes.

Les choix de duplication des données dans `Order` et `OrderDetail` sont volontaires et permettent de préserver l'historique des commandes.

Le modèle comporte également deux mécanismes de wishlist : la relation directe entre `User` et `Product` via `user_wishlist`, utilisée pour la wishlist personnelle, et l'entité `Wishlist` indépendante.

Cette structure répond aux besoins fonctionnels du site tout en laissant la possibilité d'améliorer certains choix techniques, notamment le stockage des quantités et des montants financiers.
