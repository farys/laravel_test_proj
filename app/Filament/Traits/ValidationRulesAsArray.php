<?php
namespace App\Filament\Traits;

use Filament\Forms\Components\Contracts\HasValidationRules;
use Filament\Forms\Form;

trait ValidationRulesAsArray
{
  public static function validationRules(Form $form, array $rules): void /** @var array<string, mixed> */
  {

    foreach ($form->getComponents() as $component) { /** @var \Filament\Forms\Components\Component */

      if ($component instanceof HasValidationRules) { /** @var \Filament\Forms\Components\Component */
        
        if (isset($rules[$component->getName()])) {
          $component->rules($rules[$component->getName()]);
        }
      }

      foreach ($component->getChildComponentContainers() as $container) { /** @var \Filament\Forms\ComponentContainer */
        if ($container->isHidden()) {
          continue;
        }
        foreach ($form->getComponents() as $component) { /** @var \Filament\Forms\Components\Component */

          if ($component instanceof HasValidationRules) {
            
            if (isset($rules[$component->getName()])) {
              $component->rules($rules[$component->getName()]);
            }
          }
          
          // if (isset($rules[$component->getname])) {
          //   $component->rules($rules[$component->name]);
          // }
        }

        // $rules = [
        //   ...$rules,
        //   ...$container->getValidationRules(),
        // ];
      }
    }
  }
}
