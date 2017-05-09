# OXID-DependencyManager

Dieses Modul dient der Behandlung von Abhängigkeiten zwischen einzelnen OXID-Modulen.
Einzelne Modulklassen können in OXID überladen werden, doch immer mal wieder in der Routine kann es auftreten, dass Module in der falschen Reihenfolge aktiviert werden. Dieses Modul behandelt Abhängigkeiten von Modulen unabhängig vom Deploymentstatus und abhängig von der Aktivität der notwendigen Module und Versionen.

## Anforderungen

- PHP 5.4 oder höher
- OXID CE/PE/EE in der Version 4.9.x / 5.2.x / 5.3.x

## metadata.php

Die Metadata.php muss bei Modulabhängigkeiten um ein weiteres Feld erweitert werden:

### Modulabhängigkeiten definieren

```php
'dependencies'     => array(
    'myDepModule' => array(),
),
```

### Versionsabhängigkeiten & Modulabhängigkeiten

```php
'dependencies'     => array(
    'myDepModule' => array(
        'minVersion'        => '1.0.0',
        'maxVersion'        => '1.2.0',
    ),
),
```

### Wildcard-MaxVersions definieren

```php
'dependencies'     => array(
    'myDepModule' => array(
        'minVersion'        => '1.0.0',
        'maxVersion'        => '1.*.*',
    ),
),
```

