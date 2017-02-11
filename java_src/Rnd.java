package game;

/**
 * @author erotic-games.ru
 */
import java.util.Random;
public class Rnd extends Random{

    public Rnd() {
        super();
    }
    public int get(int n){
         if (n<=0)
         throw new IllegalArgumentException("n must be positive");

         if ((n & -n) == n)  // i.e., n is a power of 2
             return (int)((n * (long)next(31)) >> 31);

         int bits, val;
         do {
             bits = next(31);
             val = bits % n;
         } while(bits - val + (n-1) < 0);
         return val;
    }
}
