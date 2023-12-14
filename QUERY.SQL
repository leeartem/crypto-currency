SELECT
    u.id AS ID,
    CONCAT(u.first_name, ' ', u.last_name) AS Name,
    b.author AS Author,
    GROUP_CONCAT(b.name) AS Books
FROM
    users as u
        JOIN user_books ub1 ON u.id = ub1.user_id
        JOIN user_books ub2 ON u.id = ub2.user_id AND ub1.id <> ub2.id
        JOIN books b ON ub1.book_id = b.id
WHERE
    TIMESTAMPDIFF(YEAR, u.birthday, CURDATE()) BETWEEN 7 AND 17
  AND DATEDIFF(ub1.return_date, ub1.get_date) <= 14
  AND DATEDIFF(ub2.return_date, ub2.get_date) <= 14
GROUP BY
    u.id
HAVING
        COUNT(DISTINCT b.id) = 2;