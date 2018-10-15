# ZF Pool Module

## Information
Nowadays, we hear "multistore", "marketplace", "multidentistry", etc. Many modern systems developing by "multi" principle. 
This mean system haі one platform but use it different user which don't now about each other.

`ZfcPool` is a universal module which has several implemented `Strategy`s for simple divide your system by global parameters.

If you marked your `Model` as `PoolAware` it will be signal to system apply global condition for all queries relative to 
this `Model`.

You no longer need to apply the same condition to all queries. You have enough to mark `Model` as `PoolAware` 
and all work will be done automatically.   

### Registered Strategies
* **ParamStrategy** - allow you to switch between different `Pool` in one interface. 
For example, you sell products on different Amazon marketplaces 
and in one moment of time you want to see details only per one marketplace. 
* **DomainStrategy** (not implemented yet) - allow you to manage different domain in one system.
* **UserStrategy** (not implemented yet) - allow you to apply different conditions relative to current user.   

## Usage
Mark you `Model` as `PoolAware`, for this use annotation `@Stagem\ZfcPool\Model\Annotation\PoolAware(fieldName="marketplace")`. 
You should pass in `fieldName` property name from `Model` and not column name from database.
In our example, we have `marketplace` field in `Model` and `marketplaceId` column in database.

```php
namespace Stagem\Product\Model;

use Doctrine\ORM\Mapping as ORM;
use Stagem\ZfcPool\Model\Annotation\PoolAware;
use Stagem\Amazon\Model\Marketplace;

/**
 * @PoolAware(fieldName="marketplace")
 * @ORM\Entity()
 * @ORM\Table(name="amazon_product_rank")
 */
class Rank
{
    //...
   
    /**
     * @var Marketplace
     * @ORM\ManyToOne(targetEntity="Stagem\Amazon\Model\Marketplace")
     * @ORM\JoinColumn(name="marketplaceId", referencedColumnName="id", nullable=true)
     */
    private $marketplace;
}
```

After this simple manipulation to all queries to *Rank* Model automatically will be added current Marketplace.

> Notice. `Stagem\Amazon\Model\Marketplace` implement `Stagem\ZfcPool\Model\PoolInterface`.