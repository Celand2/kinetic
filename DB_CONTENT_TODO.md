# DB Content Translation TODO

Ce fichier liste les contenus dynamiques affiches cote client qui viennent de la base de donnees. Ils ne doivent pas etre traduits avec `__()` tant qu'une strategie produit/base de donnees n'a pas ete validee.

## Champs detectes cote client

- `trading_cycles.name`
  - Affiche dans les vues d'investissement et le dashboard.
  - Strategie possible: `name_en` ou table `trading_cycle_translations`.

- `trading_cycles.description`
  - Affiche dans les cartes de cycles.
  - Strategie possible: `description_en` ou table de traduction liee.

- `tranches.name`
  - Affiche dans les vues d'investissement, recapitulatif et dashboard.
  - Strategie possible: `name_en` ou table `tranche_translations`.

- `tranches.description`
  - Affiche dans la selection des tranches.
  - Strategie possible: `description_en` ou table de traduction liee.

- `payment_methods.name`
  - Affiche dans les formulaires depot/retrait.
  - Strategie possible: conserver tel quel si ce sont des noms de moyens de paiement, ou ajouter `name_en` si l'admin saisit des noms localises.

- `payment_methods.details`
  - Affiche dans le formulaire de depot comme instructions de paiement.
  - Strategie recommandee: ne pas traduire automatiquement; ajouter un champ `details_en` ou une table de traduction, avec validation manuelle car ce contenu peut contenir des coordonnees de paiement.

- `transactions.description`
  - Affiche dans le detail transaction.
  - Strategie possible: garder dans la langue de saisie, ou stocker une version traduite seulement pour les descriptions systeme generees.

- `notifications.title`
  - Affiche dans la liste des notifications.
  - Strategie recommandee: pour les nouvelles notifications systeme, stocker une cle + variables plutot que du texte final; pour les notifications admin libres, prevoir `title_en`.

- `notifications.body`
  - Affiche dans la liste des notifications.
  - Strategie recommandee: idem `notifications.title`; ne pas traduire automatiquement les messages historiques.

- `notifications.action_label`
  - Affiche comme libelle de lien si defini.
  - Strategie possible: cle de traduction pour les notifications systeme, `action_label_en` pour les contenus admin.

- `conversations.subject`
  - Affiche dans la liste et le detail des messages.
  - Strategie recommandee: conserver dans la langue saisie par l'utilisateur/admin.

- `conversations.category`
  - Affiche dans la liste des messages.
  - Strategie possible: si ce sont des codes fixes, traduire via fichier `lang`; si l'admin les gere librement, ajouter traduction DB.

- `messages.body`
  - Affiche dans les fils de discussion.
  - Strategie recommandee: conserver dans la langue saisie; ne pas traduire automatiquement.

- `users.full_name`
  - Affiche dans le dashboard, messages, parrainage.
  - Ne pas traduire.

- `bonus_codes.description`
  - Potentiellement visible selon les evolutions de l'interface bonus.
  - Strategie possible: `description_en` ou table de traduction si expose cote client.

## Decision a prendre

Option simple: ajouter des colonnes `_en` pour les contenus courts et peu nombreux.

Option plus robuste: creer des tables de traduction par entite (`*_translations`) avec `locale`, `field`, `value`, utile si d'autres langues sont prevues.

Pour cette tranche, aucune modification de schema ni migration n'a ete faite.
