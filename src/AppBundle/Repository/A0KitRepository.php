<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class A0KitRepository extends \Doctrine\ORM\EntityRepository
{
    public function myFindAll_court_1()
    {
        return $this->createQueryBuilder('a')
            ->getQuery()
            ->getResult();
    }


    public function myFindAll_court_2()
    {
        $queryBuilder = $this->createQueryBuilder('a');

        $query = $queryBuilder->getQuery();

        $results = $query->getResult();

        return $results;
    }


    public function myFindAll_long()
    {
        /**
         * Création de la Requête :
         */
        $queryBuilder1 = $this->_em->createQueryBuilder()
            ->select('a')
            ->from($this->_entityName, 'a');

        // Ci-desous une méthode équivalente à celle ci-dessus, mais plus courte !!!
        $queryBuilder2 = $this->createQueryBuilder('a');

        /**
         * On récupère la Requête :
         */
        $query = $queryBuilder2->getQuery();

        /**
         * On récupère les résultats à partir de la Query :
         */
        $results = $query->getResult();

        // On retourne le résultat :
        return $results;
    }

    /*
     * Utiliser le filtre "Where" :
     */
    public function myFindOne($id)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('a')
            ->from('AppBundle:Article', 'a')
            ->where('a.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()
                ->getResult();
    }

    /*
     * Utiliser les clauses "Where" et "orderBy" :
     */
    public function findByAuteurAndDate($auteur, $annee)
    {
        $qb = $this->_em->createQueryBuilder('a')
            ->where('a.auteur = :auteur')
            ->setParameter('auteur', $auteur)
            ->andWhere('a.date < :annee')
            ->setParameter('annee', $annee)
            ->orderBy('a.date', 'DESC');

        return $qb->getQuery()
            ->getResult();

    }


    /*
     * Fonction réutilisable en la collanT à un query existant
     * pour récupérer des résultats filtrés à l'année en cours (du 01 janvier au 31 décembre) :
     */
    public function whereCurrentYear(\Doctrine\ORM\QueryBuilder $qb)
    {
        $qb->andWhere('a.date BETWEEN :debut AND :fin')
            // Date entre le 1er janvier de cette année
            ->setParameter('debut', new \DateTime(date('Y').'-01-01'))

            // et le 31 décembre de cette année
            ->setParameter('fin', new \DateTime(date('Y').'-12-31'));

        // On retourne un QueryBuilder et non pas un Query ou un Result, mais bien un QueryBuilder
        // afin qu'il soit traité ultérieurement au sein de l'a méthode qui appelle celle-ci :
        return $qb;
    }


    /*
     * Utiliser la méthode "whereCurrentYear" dans une autre méthode :
     */
    public function useWhereCurrentYear()
    {
        $qb = $this->_em->createQueryBuilder('a')
            ->where('a.auteur = :auteur');

        // On applique la méthode importée :
        $qb->whereCurrentYear($qb);

        // On peut ajouter ce qu'on veut après :
        $qb->orderBy('a.date', 'DESC');

        return $qb->getQuery()
            ->getResult();
    }



    /**
     * ---------------------------------------------------------------------------------
     *      L'objet QUERY et les différentes méthodes pour récupérer des résultats :
     * ---------------------------------------------------------------------------------
     */

    public function different_ways_to_use_the_Query_object()
    {
        $query = $this->createQueryBuilder('a')
            ->getQuery();

         /*
          * getResult() :
          * -------------
          * Exécute la requête et retourne un tableau contenant les résultats sous forme d'objets.
          * Vous récupérez la liste des objets sur lesquels vous pouvez faire des opérations, des modifications, etc.
          * Vous récupérez un tableau, même s'il n'y a qu'un seul résultat.
          */
         $results1 =  $query->getResult();

         foreach ($results1 as $entity) {
             $auteurs[] = $entity->getAuteur();
         }


         /*
          * getArrayResult() :
          * ------------------
          * Exécute la requête et retourne un tableau contenant les résultats sous forme de tableaux.
          * Vous récupérez un tableau, même s'il n'y a qu'un seul résultat.
          * Mais dans ce tableau, vous n'avez pas vos objets d'origine, mais vous avez de simples tableaux.
          * Cette méthode est utilise lorsque vous ne voulez que lire les résultats, sans y apporter de modification.
          * Elle est plus rapide que son homologue "getResult()".
          */
         $results2 = $query->getArrayResult();

        foreach ($results2 as $entity) {
            $auteurs[] = $entity['auteur'];
        }


        /*
         * getScalarResult() :
         * -------------------
         * Exécute la requête et retourne les résultats sous forme de valeurs.
         * Vous récupérez un tableau, même s'il n'y a qu'un seul résultat.
         * Cette méthode est utile lorsque vous ne sélectionnez qu'une seule valeur dans la requête,
         * par exemple : "SELECT COUNT(*) FROM ...".
         * Ici, la valeur en résultat est celle de COUNT.
         */
        $results3 = $query->getScalarResult();
        // Faire $result3->getAttribut() ou $result3['attribut'] est impossible ici !


        /*
         * getOneOrNullResult() :
         * ----------------------
         * Exécute la requête et retourne un seul résultat, ou null si pas de résultat.
         * Cette méthode retourne donc une instance de l'entité (ou null) et non un tableau d'entité (comme "getResult()").
         * Cette méthode déclenche une Exception si la requête retourne plus d'un résultat !
         */
        $results4 = $query->getOneOrNullResult();

        $auteur = $results4->getAuteur();


        /*
         * getSingleResult() :
         * -------------------
         * Exécute la requête et retourne un seul résultat.
         * Cette méthode est la même que "getOneOrNullResult()", sauf qu'elle déclenche un Exception s'il n'y a aucun résultat.
         * Elle est très utilisée car faire des requêtes qui ne retournent qu'un seul résultat est très fréquent !
         */
        $results5 = $query->getSingleResult();

        $auteur = $results5->getAuteur();



        /*
         * getSingleScalarResult() :
         * -------------------------
         * Exécute la requête et retourne une seule valeur.
         * Elle déclenche une Exception s'il n'y a pas de résultat, ou s'il y a plus d'un résultat !
         * Cette méthode est très utilisée également pour des requêtes du type "SELECT COUNT(*) FROM ARTICLE" qui en retourne qu'une seule ligne de résultat, et une seule valeur dans cette ligne :
         */
        $results6 = $query->getSingleScalarResult();

        $nombreDeValeurs = $results6;


        /*
         * execute() :
         * -----------
         * Exécute la requête.
         * Cette méthode est utilisée principalement pour exécuter des requêtes qui ne retournent pas de résultats (des "UPDATE", "INSERT INTO", ...)
         * En fait, toutes les autres méthodes que nous venons de voir ne sont en fait que des raccourcis vers cette méthode "execute()", en changeant juste le mode d'hydratation des résultats (objet, tableau, etc.).
         */
        // Exécute un UPDATE par exemple :
        $query->execute();

        // Ci-dessous deux méthodes strictement équivalentes :
        $results7 = $query->getArrayResult();

        // et
        $results8 = $query->execute(array(), Query::HYDRATE_ARRAY);
        // Le premier argument de "execute()" est un tableau de paramètres.
        // Vous pouvez aussi passer par la méthode "setParameter()" au choix.
        // Le deuxième argument est la méthode d'hydratation


    }



    /**
     * --------------------------------------------------------------------------
     *                                      DQL
     * --------------------------------------------------------------------------
     */

    public function my_FindAll_DQL()
    {
        $query = $this->_em->createQuery('SELECT  a FROM AppBundle:Article a');

        // Pour ne sélectionner qu'une propriété particulière de l'objet :
        $query2 = $this->_em->createQuery('SELECT  a.auteur FROM AppBundle:Article a');

        $result = $query->getResult();

        return $result;
    }



    public function special_sql_functions_DQL()
    {
        // Utilisation de la fonction "TRIM" :
        $query1 = $this->_em->createQuery(
            "SELECT a
                    FROM Article a
                    WHERE TRIM(a.auteur) = 'winzou'"
        );

        // Utilisation de la fonction "IN()" :
        $query2 = $this->_em->createQuery(
            "SELECT a
                    FROM Article a
                    WHERE a.id IN(1, 3, 5)"
        );

        // Utilisation de Paramètres :
        $query3 = $this->_em->createQuery(
            "SELECT a
                    FROM Article a
                    WHERE a.id = :id"
        );
        $id = 3;
        $query3->setParameter('id', $id);

        return $query3->getSingleResult();
    }


    /** ------------------------------------------------------------------------
     *                                 Jointures:
     * -------------------------------------------------------------------------
     */

    public function jointures_DQL()
    {
        // Jointure 1 (DQL) :
        // ------------------
        $query1 = $this->_em->createQuery(
            'SELECT  a, u 
                    FROM AppBundle:Article a 
                    JOIN a.utilisateur u 
                    WHERE a.age = 25'
        );



        // Jointure 2 (QueryBuilder) :
        // ---------------------------
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.commentaires', 'c')
            ->addSelect('c');

        /* Le premier argument de la méthode "leftJoin()" est l'attribut de l'entité principale (celle qui est dans le FROM de la requête) sur lequel faire la jointure. Dans l'exemple, l'entité Article possède l'attribut "commentaires".
            Le deuxième argument de la méthode est l'alias de l'entité jointe.
            Puis on sélectionne l'entité jointe, via un "addSelect()".
            En effet, un "select()" tout court aurait écrasé le "select('a')" déjà fait par le "createQueryBuilder('a')".
        */
        return $qb->getQuery()
            ->getResult();



        // Jointure 3 (QueryBuilder avec un "WITH") :
        // ------------------------------------------
        $qb = $this->createQueryBuilder('a')
            ->join('a.commentaires', 'c', 'WITH', 'YEAR(c.date) > 2018')
            ->addSelect('c');
        /* C'est quoi cette syntaxe avec "WITH" pour faire une jointure ?
            En SQL, la différence entre le "ON" et le "WITH" est simple :
            => un "ON" définit la condition pour la jointure
                alors qu'un "WITH" ajoute une condition pour la jointure.

            Attention ! en DQL le "ON" n'existe pas, seul le "WITH" est supporté.

            Ainsi, la syntaxe précédente avec le "WITH" serait équivalente à la syntaxe SQL suivante à base de "ON" :
                "SELECT * FROM Artile a JOIN Commentaire c ON c.article = a.id AND YEAR(c.date) > 2018"

            Grâce au "WITH", on n'a pas besoin de réécrire la condition par défaut de la jointure, le "c.article = a.id" !
        */

    }


}
