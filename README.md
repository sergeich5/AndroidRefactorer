# AndroidRefactorer
Simple php script to change android app package name

# Usage

1) Open terminal in Android app project directory

``cd <Android app Project>``

2) Run script

``php <PATH TO refactor_pkg.php>/refactor_pkg.php``

3) Rename ``app/src/*`` folders according to your new package name
4) **Optionally**: add new ``google-services.json``

# How it works

1) Script looks for ``find`` string your enter
2) Replaces with ``replace`` string your enter
3) Script also deletes ``google-services.json`` file and ``.idea/``, ``.gradle/``, ``build/`` folders 