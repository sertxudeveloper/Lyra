## Lyra Installation

Add this code to the composer.json

            "repositories": [
              {
                "type": "path",
                "url": "../lyra/",
                "options": {
                  "symlink": true
                }
              }
            ]

and this line to the "require" object

    "sertxudeveloper/lyra": "*"

execute 

  php artisan vendor:publish --tag lyra-assets