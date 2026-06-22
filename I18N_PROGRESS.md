# I18N Progress

## Socle termine

- `config/app.php` utilise `fr` comme `locale` et `fallback_locale` par defaut.
- `.env` et `.env.example` declarent `APP_LOCALE=fr`, `APP_FALLBACK_LOCALE=fr`, `APP_FAKER_LOCALE=fr_FR`.
- `lang/fr` et `lang/en` ont ete crees.
- `App\Http\Middleware\SetLocale` applique la langue stockee en session.
- `GET /lang/{locale}` bascule entre `fr` et `en`.
- Le layout client `resources/views/layouts/client.blade.php` contient un selecteur de langue sans nouveau CSS.
- La page d'accueil `resources/views/index.blade.php` contient aussi un selecteur de langue dans la navigation existante.

## Vues client traitees dans cette tranche

- `resources/views/layouts/client.blade.php`
- `resources/views/index.blade.php` (selecteur de langue uniquement)
- `resources/views/client/dashboard/index.blade.php`
- `resources/views/client/transactions/index.blade.php`
- `resources/views/client/transactions/deposit.blade.php`
- `resources/views/client/messages/index.blade.php`
- `resources/views/client/notifications/index.blade.php`
- `resources/views/client/redeem-bonus.blade.php`

## Vues client restantes

- `resources/views/client/investments/create.blade.php`
- `resources/views/client/investments/index.blade.php`
- `resources/views/client/investments/invest.blade.php`
- `resources/views/client/investments/show.blade.php`
- `resources/views/client/investments/tranches.blade.php`
- `resources/views/client/messages/create.blade.php`
- `resources/views/client/messages/inbox.blade.php`
- `resources/views/client/messages/sent.blade.php`
- `resources/views/client/messages/show.blade.php`
- `resources/views/client/referral/dashboard.blade.php`
- `resources/views/client/transactions/show.blade.php`
- `resources/views/client/transactions/withdraw.blade.php`

## Notes de prudence

- Les vues admin n'ont pas ete modifiees.
- Les textes dynamiques venant de la base de donnees n'ont pas ete traduits automatiquement.
- Certains libelles metier encore calcules depuis des codes (`status`, `type`, etc.) devront etre traites progressivement dans les vues restantes ou via helpers dedies.
