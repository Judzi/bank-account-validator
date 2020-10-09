# Symfony form constraint for Czech bank account number
Implemented czech bank account number validator by @malja GIST (https://gist.github.com/malja/4fbe9b69878fc81dd2dd77c57fc059a9)

## Usage
```php
$builder->add('bankAccountNumber', TextType::class, [
    'constraints' => [new CzechBankAccountNumber(['message' => 'bankAccountNumber.format'])]
]);
```
