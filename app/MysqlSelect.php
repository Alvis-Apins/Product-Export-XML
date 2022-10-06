<?php

namespace App;

class MysqlSelect
{
    public function getSelection($connection)
    {
        $query = "SELECT 
            product.model, product.status, product.quantity, product.ean, product.image, product.date_added, product.price,
            product_description.name, product_description.meta_description, product_description.product_id, product_description.language_id,
            product_special.price, product_special.date_start, product_special.date_end
        FROM Products.product AS product
        RIGHT JOIN Products.product_description AS product_description
        ON product_description.product_id = product.product_id
        LEFT JOIN Products.product_special AS product_special
        ON product_special.product_id = product.product_id
        ORDER BY product_description.product_id";

        $result = $connection->query($query);
        return $result->fetch_all();
    }

}