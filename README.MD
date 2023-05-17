Hasło do strony to: macopedia

W tabeli products zmieniona została nazwa kolumny z index na product_index,
poniważ sqlite nie pozwala na nazwanie kolumny 'index'

Zapytania:
SELECT COUNT(u.id) as 'ilość filmów', 'Angielski' as 'język' FROM film f
LEFT JOIN `languauge` l ON
f.user_id = l.id
WHERE l.name =  'Angielski'

SELECT COUNT(u.id) as 'ilość filmów', 'Niemiecki' as 'język' FROM film f
LEFT JOIN `languauge` l ON
f.user_id = l.id
WHERE l.name =  'Niemiecki'

SELECT COUNT(u.id) as 'ilość filmów', 'Polski' as 'język' FROM film f
LEFT JOIN `languauge` l ON
f.user_id = l.id
WHERE l.name =  'Polski'

SELECT COUNT(u.id) as 'ilość filmów', 'Angielski' as 'język' FROM film f
LEFT JOIN `languauge` l ON
f.user_id = l.id
WHERE l.name =  'Angielski'
HAVING COUNT(*) > 5

SELECT COUNT(u.id) as 'ilość filmów', 'Niemiecki' as 'język' FROM film f
LEFT JOIN `languauge` l ON
f.user_id = l.id
WHERE l.name =  'Niemiecki'
HAVING COUNT(*) > 5
