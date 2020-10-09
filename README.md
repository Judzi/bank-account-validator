# Czech bank account number validator for Symfony
Implemented czech bank account number validator by @malja GIST (https://gist.github.com/malja/4fbe9b69878fc81dd2dd77c57fc059a9)

## Data
Bank list - **Last updated 10th of October 2020** (https://www.cnb.cz/cs/platebni-styk/ucty-kody-bank/)

## Usage
```php
$builder->add('bankAccountNumber', TextType::class, [
    'constraints' => [new CzechBankAccountNumber(['message' => 'bankAccountNumber.format'])]
]);
```
