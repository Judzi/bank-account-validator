<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CzechBankAccountNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) {
            return;
        }

        if (!$this->isValid($value)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }

    /**
     * @param string $value Řetězec obsahující číslo bankovního účtu ve formátu [prefix]-[základní část]/[kód banky].
     *                      Prefix je volitelná část obsahující až 6 čísel. Od základní části je oddělen pomlčnou.
     *                      Základní část se skládá ze 2 až 10 čísel.
     *                      Kód banky je složen ze 4 čísel oddělených od základní části lomenem.
     * @return bool
     */
    private function isValid(string $value): bool
    {
        // Váhy pro kontrolu prefixu
        $prefixWeights = [10, 5, 8, 4, 2, 1];

        // Váhy pro kontrolu základní části čísla
        $baseWeights = [6, 3, 7, 9, 10, 5, 8, 4, 2, 1];

        // Kontrola formátu
        if (!preg_match('/^(([0-9]{0,6})-)?([0-9]{2,10})\/([0-9]{4})$/', $value, $parts)) {
            return false;
        }

        // Kontrola prefixu
        if (!empty($parts[2])) {

            // Doplnění na 6 číslic nulami zleva
            $prefix = str_pad($parts[2], 6, "0", STR_PAD_LEFT);

            // Suma všech čísel pronásobených jejich váhami
            $sum = 0;
            for ($i = 0; $i < 6; $i++) {
                $sum += intval($prefix[$i]) * $prefixWeights[$i];
            }

            // Kontrola na dělitelnost 11
            if ($sum % 11 != 0) {
                return false;
            }

        }

        // Doplnění na 10 číslic nulami zleva
        $base = str_pad($parts[3], 10, "0", STR_PAD_LEFT);

        // Suma všech číslic pronásobených jejich vahami
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += intval($base[$i]) * $baseWeights[$i];
        }

        // Kontrola na dělitelnost 11
        if ($sum % 11 != 0) {
            return false;
        }

        // Kontrola bankovního čísla
        $code = $parts[4];
        if (empty(self::bankList()[$code])) {
            return false;
        }

        return true;
    }

    /**
     * @return string[]
     *
     * @note Seznam všech aktuálně působících bank je možné najít ve formátu
     *       CSV na stránce: https://www.cnb.cz/cs/platebni-styk/ucty-kody-bank/
     */
    private static function bankList(): array
    {
        return [
            '0100' => 'Komerční banka, a.s.',
            '0300' => 'Československá obchodní banka, a. s.',
            '0600' => 'MONETA Money Bank, a.s.',
            '0710' => 'ČESKÁ NÁRODNÍ BANKA',
            '0800' => 'Česká spořitelna, a.s.',
            '2010' => 'Fio banka, a.s.',
            '2020' => 'MUFG Bank (Europe) N.V. Prague Branch',
            '2030' => 'Československé úvěrní družstvo',
            '2060' => 'Citfin, spořitelní družstvo',
            '2070' => 'TRINITY BANK a.s.',
            '2100' => 'Hypoteční banka, a.s.',
            '2200' => 'Peněžní dům, spořitelní družstvo',
            '2220' => 'Artesa, spořitelní družstvo',
            '2240' => 'Poštová banka, a.s., pobočka Česká republika',
            '2250' => 'Banka CREDITAS a.s.',
            '2260' => 'NEY spořitelní družstvo',
            '2275' => 'Podnikatelská družstevní záložna',
            '2600' => 'Citibank Europe plc, organizační složka',
            '2700' => 'UniCredit Bank Czech Republic and Slovakia, a.s.',
            '3030' => 'Air Bank a.s.',
            '3050' => 'BNP Paribas Personal Finance SA, odštěpný závod',
            '3060' => 'PKO BP S.A., Czech Branch',
            '3500' => 'ING Bank N.V.',
            '4000' => 'Expobank CZ a.s.',
            '4300' => 'Českomoravská záruční a rozvojová banka, a.s.',
            '5500' => 'Raiffeisenbank a.s.',
            '5800' => 'J & T BANKA, a.s.',
            '6000' => 'PPF banka a.s.',
            '6100' => 'Equa bank a.s.',
            '6200' => 'COMMERZBANK Aktiengesellschaft, pobočka Praha',
            '6210' => 'mBank S.A., organizační složka',
            '6300' => 'BNP Paribas S.A., pobočka Česká republika',
            '6700' => 'Všeobecná úverová banka a.s., pobočka Praha',
            '6800' => 'Sberbank CZ, a.s.',
            '7910' => 'Deutsche Bank Aktiengesellschaft Filiale Prag, organizační složka',
            '7940' => 'Waldviertler Sparkasse Bank AG',
            '7950' => 'Raiffeisen stavební spořitelna a.s.',
            '7960' => 'Českomoravská stavební spořitelna, a.s.',
            '7970' => 'MONETA Stavební Spořitelna, a.s.',
            '7980' => 'Wüstenrot hypoteční banka a.s.',
            '7990' => 'Modrá pyramida stavební spořitelna, a.s.',
            '8030' => 'Volksbank Raiffeisenbank Nordoberpfalz eG pobočka Cheb',
            '8040' => 'Oberbank AG pobočka Česká republika',
            '8060' => 'Stavební spořitelna České spořitelny, a.s.',
            '8090' => 'Česká exportní banka, a.s.',
            '8150' => 'HSBC France - pobočka Praha',
            '8190' => 'Sparkasse Oberlausitz-Niederschlesien',
            '8198' => 'FAS finance company s.r.o.',
            '8199' => 'MoneyPolo Europe s.r.o.',
            '8200' => 'PRIVAT BANK der Raiffeisenlandesbank Oberösterreich Aktiengesellschaft, pobočka Česká republika',
            '8215' => 'ALTERNATIVE PAYMENT SOLUTIONS, s.r.o.',
            '8220' => 'Payment execution s.r.o.',
            '8225' => 'ORANGETRUST s.r.o.',
            '8230' => 'EEPAYS s.r.o.',
            '8240' => 'Družstevní záložna Kredit',
            '8250' => 'Bank of China (CEE) Ltd. Prague Branch',
            '8255' => 'Bank of Communications Co., Ltd., Prague Branch odštěpný závod',
            '8260' => 'PAYMASTER a.s.',
            '8265' => 'Industrial and Commercial Bank of China Limited, Prague Branch, odštěpný závod',
            '8270' => 'Fairplay Pay s.r.o.',
            '8272' => 'VIVA PAYMENT SERVICES S.A. odštěpný závod',
            '8280' => 'B-Efekt a.s.',
            '8283' => 'Qpay s.r.o.',
            '8291' => 'Business Credit s.r.o.',
            '8292' => 'Money Change s.r.o.',
            '8293' => 'Mercurius partners s.r.o.',
            '8294' => 'GrisPayUnion s.r.o.',
            '8296' => 'PaySysEU s.r.o.',
        ];
    }
}
