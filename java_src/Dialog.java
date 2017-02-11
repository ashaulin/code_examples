package game;

import javax.microedition.lcdui.*;
import javax.microedition.lcdui.game.*;

/**
 *
 * @author erotic-games.ru
 */

public class Dialog extends Canvas{
    private MultiLineText MLT = new MultiLineText();
    private String comDone, comNone, text;
    //private int dnx, dny, ddx, dox, btnh;
    private Player player;
    private boolean isOponent;
    private int grH, grW;
    private static int btn_color=0x838282;
    private static int btn_frm_color=0xCB1616;
    private Font fnt = Font.getFont(Font.FACE_PROPORTIONAL, Font.STYLE_PLAIN, Font.SIZE_SMALL);
    private int fntH = fnt.getHeight();
    private Image bgSibl;
    private boolean isFirstRun=true;  //Флаг первого отображения текста задания
    public Dialog(boolean _isOponent, String _comDone, String _comNone, String _text, Player _pl, Image _bgSibl){
        setFullScreenMode(true);
        player = _pl;
        comDone = _comDone;
        comNone = _comNone;
        isOponent = _isOponent;
        text = _text;
        bgSibl = _bgSibl;
        grH=getHeight();
        grW=getWidth();
    }
    public void paint(Graphics g){
        int fntW = fnt.stringWidth(comDone);
        int scrH = g.getFont().getHeight();
        //очищаем экран
        g.setColor(0x555555);
        g.fillRect(0, 0, grW, grH);
        //подложка
        g.drawImage(player.ml.bgrnd, (grW - player.ml.bgrnd.getWidth())/2, (grH - player.ml.bgrnd.getHeight())/2, Graphics.TOP|Graphics.LEFT);
        //значок задания
        g.drawImage(bgSibl, grW-bgSibl.getWidth()-6, grH-fntH*2-6-bgSibl.getHeight(), Graphics.TOP|Graphics.LEFT);
        //верхняя рамка
        g.drawImage(player.ml.hdr, 0, scrH+2, Graphics.TOP|Graphics.LEFT);
        //пишем "Очки игрока"
        g.setColor(0xFF3F3F);
        g.drawString(player.name+": "+player.scr+" of charm", 4, 2, Graphics.TOP|Graphics.LEFT);
        g.setFont(fnt);
        //пишем "выполнено"
        g.setColor(btn_color);
        g.fillRect(0, grH-fntH-4, fntW+8, fntH+2);
        g.setColor(btn_frm_color);
        g.drawRect(0, grH-fntH-4, fntW+8, fntH+2);
        g.drawString(comDone, 4, grH-fntH-2, Graphics.TOP|Graphics.LEFT);
        //пишем "невыполнено"
        fntW = fnt.stringWidth(comNone);
        g.setColor(btn_color);
        g.fillRect(grW-fntW-10, grH-fntH-4, fntW+8, fntH+2);
        g.setColor(btn_frm_color);
        g.drawRect(grW-fntW-10, grH-fntH-4, fntW+8, fntH+2);
        g.drawString(comNone, grW-fntW-6, grH-fntH-2, Graphics.TOP|Graphics.LEFT);
        if(isOponent){
            //пишем "опонент отказался"
            fntW = fnt.stringWidth("Refused");
            g.setColor(btn_color);
            g.fillRect((grW-fntW)/2-2, grH-fntH*2-8, fntW+8, fntH+2);
            g.setColor(btn_frm_color);
            g.drawRect((grW-fntW)/2-2, grH-fntH*2-8, fntW+8, fntH+2);
            g.drawString("Refused", (grW-fntW)/2+2, grH-fntH*2-6, Graphics.TOP|Graphics.LEFT);
        }
        g.setColor(0xffffff);
        //выводим текст задания
        if(isFirstRun){
            MLT.SetTextPar(2, player.ml.hdr.getHeight()+scrH+4, grW-2,
                grH-fntH*2-8-(player.ml.hdr.getHeight()+scrH+4), 5, Font.SIZE_SMALL,
                Font.STYLE_PLAIN, Font.FACE_PROPORTIONAL, g, text);  
            isFirstRun = false;
        }
        MLT.DrawMultStr();
    }
    public void keyPressed(int keyCode){
       //Организуем прокрутку текста.    
       if (keyCode == getKeyCode(UP)){
           MLT.MoveUp();
           repaint();         
       }else if(keyCode == getKeyCode(DOWN)){
           MLT.MoveDown();
           repaint();         
       }else if(keyCode == getKeyCode(LEFT)){
           MLT.PageUp();
           repaint();         
       }else if(keyCode == getKeyCode(RIGHT)){
           MLT.PageDown();
           repaint();         
       }else if(keyCode == KEY_NUM4 || keyCode == KEY_NUM1 || keyCode == KEY_NUM7
               || keyCode == KEY_STAR || getKeyName(keyCode).compareTo("SOFT1") == 0
               || keyCode == -6)
           player.done();
       else if(keyCode == KEY_NUM6 || keyCode == KEY_NUM3 || keyCode == KEY_NUM9
               || keyCode == KEY_POUND || getKeyName(keyCode).compareTo("SOFT2") == 0
               || keyCode == -7)
           player.none();
       else if(isOponent && (keyCode == KEY_NUM5 || keyCode == KEY_NUM2
               || keyCode == KEY_NUM8 || keyCode == KEY_NUM0
               || getKeyName(keyCode).compareTo("SELECT") == 0 || keyCode == -5) )
           player.oponent();
       /*Alert a = new Alert("Mess","keyCode: "+keyCode+"\nKeyName: "+getKeyName(keyCode),null,null);
        player.ml.disp.setCurrent(a);

        }*/
    }

}
