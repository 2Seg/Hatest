# Hatest

Hatest allows you to test your entities' properties, getters and setters.

## Usage

Your test file must extends `PHPUnit\Framework\TestCase` and implements `Hatest\Interfaces\GetterSetterInterface`.

Use `Hatest\Traits\GetterTrait`, `Hatest\Traits\SetterTrait` and `Hatest\Doctrine\Traits\CollectionTrait` if needed.

Then, it should look something like this :

    // ...
    use PHPUnit\Framework\TestCase;
    use Hatest\Interfaces\GetterSetterInterface;
    use Hatest\Traits\GetterTrait;
    use Hatest\Traits\SetterTrait;
    use Hatest\Doctrine\Traits\CollectionTrait;
    // ...
    
    class UserTest extends TestCase implements GetterSetterInterface
    {
        use GetterTrait, SetterTrait, CollectionTrait;
        
        public function init()
        {
            return new User();
        }
        
        public function providerGetterAndSetter()
        {
            return [
                ['username', 'test'],
                ['password', 'test'],
                ['email', 'test'],
                ['lastConnection', new \DateTime()],
            ];
        }
    
        public function providerCollection(): array
        {
            return [
                ['role', 'test'],
                ['friend', new User()],
            ];
        }
    }
    
## Options

Alternatively, it's possible to specify the name of a getter or a setter.

    // ...
    
    class UserTest extends TestCase implements GetterSetterInterface
    {
        // ...
        
        public function providerGetterAndSetter()
        {
            return [
                // ...
                ['access', true, [
                    'getter' => 'hasAccess',
                ]],
            ];
        }
        
        // ...
    }
    
The possibility is also available for collections.
Available options are `'getter'`, `'remover'`, `'haser'`, `'adder'`.

    // ...
    
    class UserTest extends TestCase implements GetterSetterInterface
    {
        // ...
        
        public function providerCollection():array
        {
            return [
                // ...
                ['availabilities', new Availability(), [
                    'getter' => 'getAvailability',
                ]],
            ];
        }
        
        // ...
    }
    
