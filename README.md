IMG Factory
===========

Pré-requis
-------------

 - PHP 5.6.22
 - Apache 2.4.10

Installation
-------------

**Back-end :**

Pour installer le projet :

 - Cloner le dépôt git
 - Donner les droits d'écritures au dossier data/

Pour enrichir les samples :

 - Ajouter des images dans les sous dossiers du dossier samples/
 
Description
-----------
 
 http://img.recette.octaveoctave.com/data/cat-200-100-000000-ffffff.png
 
 - http://img.recette.octaveoctave.com/ : racine du site
 - data/ : folder d'appel
 - cat : OPTION FACULTATIVE : catégorie de l'image, fait référence au sous dossier se trouvant dans le dossier samples/
 - 200 : largeur de l'image
 - 100 : hauteur de l'image
 - 000000 : couleur de l'arrière plan du texte
 - ffffff : couleur du texte
 
 Si l'image appelée existe dans le dossier data, elle est affichée. Si ce n'est pas le cas elle est générée.