/*
 * Класс MultiLineText предназначен для отображения многострочного текста.
 * Автор А.С. Ледков
 * www.mobilab.ru
 * ledkov@inbox.ru
 */

package game;

import java.util.Vector;
import javax.microedition.lcdui.*;

public class MultiLineText {
    private int x,y,w,h,fsz,fst,fty;    //Размер ограничивающего прямоугольника;
    private int hStr;       //Высота строки
    private int y0;         //Положение верхнего края текста
    private int dy;         //Шаг при прокрутке текста
    private int textheight; //Общая высота текста
    private Vector StringLines;    
    private Graphics g;
    private int gx,gy,gw,gh; //Исходная область
    
    private String str1;

    public void  MoveDown()
    {
        if (textheight>h)
        {            
            y0=y0-dy;
            if (h-y0>textheight) {y0=h-textheight;}
        }
    }
    
    public void MoveUp()
    {
        if (textheight>h)
        {
            y0=y0+dy;
            if (y0>0){y0=0;}
        }
        
    }

public void PageUp()
    {
        if (textheight>h)
        {
           y0=y0+h;
           if (y0>0){y0=0;} 
        }
        
    }

public void PageDown()
    {
        if (textheight>h)
        {
            y0=y0-h;
            if (h-y0>textheight) {y0=h-textheight;}
        }         
    }
            
    public void SetTextPar(
            int x, 
            int y,
            int width,
            int height,
            int dy,
            int FontSize,
            int FontStyle,
            int FontType,
            Graphics graph,
            String LongString
            )
    {
        this.x=x;
        this.y=y;
        this.w=width;
        this.h=height;
        this.dy=dy;
        this.fsz=FontSize;
        this.fst=FontStyle;
        this.fty=FontType;
        this.g=graph;
        gx=g.getClipX();
        gy=g.getClipY();
        gw=g.getClipWidth();
        gh=g.getClipHeight();
        g.setFont(Font.getFont(fty, fst, fsz));
        //Разбиваем строку на массив строк
        StringLines=null;
        StringLines =new Vector(1);
        int i0=0,i=0,in=0,j,jw=0;   //Сещение от начала строки
        int imax=LongString.length();   //Длина строки
        hStr=g.getFont().getHeight();
        boolean isexit=true;
        y0=0;
        while (isexit)
        {
            i=LongString.indexOf(" ", i0+1);
            if (i<=i0)
            {
                i=imax;
                isexit=false;
            }
                
            
            j=g.getFont().stringWidth(LongString.substring(i0,i));
            if (jw+j<w)
            {//Слово умещается
                jw=jw+j;
                i0=i;
            } else 
            {//Слово не умещается
                //System.out.println("in="+in+" i0="+i0+" Str=["+LongString.substring(in,i0)+"]");
                StringLines.addElement(LongString.substring(in,i0));                
                in=i0+1;
                jw=j;
                if (j>w)
                {//Слово полностью не умещается в строке
                    
                    i=i0;
                  while (jw>w)
                  {
                    j=0;  
                    while (j<w)
                    {
                        i=i+1;
                        j=g.getFont().stringWidth(LongString.substring(in,i));
                    
                    }
                    i=i-1;
                    j=g.getFont().stringWidth(LongString.substring(in,i));
                    StringLines.addElement(LongString.substring(in,i));
                    jw=jw-j;
                    i0=i;                
                    in=i;
                  }
                  jw=0;                    
                }else{i0=i;}                
            }            
        }
        StringLines.addElement(LongString.substring(in,imax));
        textheight=StringLines.size()*hStr;
    }
    
    public void DrawMultStr()
    {       
       int y1;       
       g.setClip(x, y, w, h);
       y1=y0;
       g.setFont(Font.getFont(fty, fst, fsz));
       for (int i=0;i<StringLines.size();i++)
       {                
           if ((y1+hStr)>0){
           g.drawString(StringLines.elementAt(i).toString(), x+1, y+y1, g.LEFT|g.TOP);           
           }
           y1=y1+hStr; 
           if (y1>h){break;}
       }
       g.setClip(gx, gy, gw, gh);
       
    }
            
            

}
