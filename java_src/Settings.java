package game;

import java.io.IOException;
import javax.microedition.midlet.*;
import javax.microedition.lcdui.*;
import java.util.*;
import javax.microedition.io.ConnectionNotFoundException;

//import javax.microedition.lcdui.game.*;

/**
 *
 * @author erotic-games.ru
 */
public class Settings extends Form implements CommandListener, ItemStateListener, ItemCommandListener{
    private Midlet ml;
    private Command exit = new Command("Exit", Command.SCREEN, 2);
    private Command play = new Command("Play!", Command.EXIT, 1); // про EXIT - это не ошибка, это чтобы эта команда была приоритетней
    private Command back = new Command("Back", Command.BACK, 5);
    private Command ok = new Command("Change", Command.OK, 2);
    public Image bg,sp;
    private ImageItem bgimg;
    private StringItem quest = new StringItem("", "Select questions", StringItem.BUTTON);
    private StringItem URL = new StringItem("", "http://sexsexgame.com", StringItem.HYPERLINK);
    public TextField n = new TextField("Players:", "2", 2, TextField.NUMERIC);
    private String[] setStr = {"Soft version", "Infinite game"};
    private ChoiceGroup isSI = new ChoiceGroup("Settings", ChoiceGroup.MULTIPLE, setStr, null);
    private Form f = new Form("New Seduction"); // форма с вопросами
    private static String[] str = {"All", "Masturbation", "Porn", "Unisex sex", "BDSM",
            "Sex with old men", "Group sex", "Unusual sex"};
    private ChoiceGroup qCh = new ChoiceGroup("Ask questions about:", ChoiceGroup.MULTIPLE, str, null);
    private Font fnt = Font.getFont(Font.FACE_PROPORTIONAL, Font.STYLE_PLAIN, Font.SIZE_SMALL);
    static final byte ELEM = 7;
    public byte pN = 2;
    public boolean isSoft, isInf;
    public boolean[]allowGrps = {false,false,false,false,false,false,false,false}; //разрешенные вопросы
    public Settings(Midlet midlet){
        super("New Seduction");
        ml = midlet;
        // Создаём форму с вопросами        
        qCh.setSelectedFlags(allowGrps);
        f.append(qCh);
        //f.append(URL);
        f.addCommand(play);
        f.addCommand(back);
        f.setCommandListener(this);
        f.setItemStateListener(this);
        try{
            bg = Image.createImage("/bg.png");
            sp = Image.createImage("/sp.png");
        }
        catch (IOException ex){
            Alert a = new Alert("Error","Can't load pictures for settings. "+ex.getMessage(), null, AlertType.ERROR);
            ml.disp.setCurrent(a);
        }
        bgimg = new ImageItem("", bg, ImageItem.LAYOUT_CENTER|ImageItem.LAYOUT_EXPAND, "New Seduction");
        isSI.setSelectedIndex(0, true);
        isSI.setLayout(ChoiceGroup.LAYOUT_CENTER);
        isSI.setFont(0, fnt); isSI.setFont(1, fnt);
        n.setLayout(TextField.LAYOUT_CENTER);
        n.setMaxSize(2);
        //n.setPreferredSize(3, 3);
        quest.setDefaultCommand(new Command("Change", Command.ITEM, 4));
        URL.setDefaultCommand(new Command("Goto", Command.HELP, 6));
        quest.setItemCommandListener(this);
        URL.setItemCommandListener(this);
        qCh.setItemCommandListener(this);
        append(bgimg);
        append(URL);
        append(isSI);
        append(quest);
        append(n);
        append(sp);
        
        addCommand(play);
        addCommand(exit);
        createNameFld("Player 1", true, 0);
        createNameFld("Player 2", false, 1);
        setItemStateListener(this);
        setCommandListener(this);
  }
    private void createNameFld(String name, boolean isMale, int i){
        ml.player.addElement(new Player(name, isMale));
        append(setGender(isMale, i));
        append(new TextField("Name: ", name, 12, TextField.LAYOUT_LEFT));
        append(sp);
    }
    private StringItem setGender(boolean isMale, int i) {
        StringItem img;
        String gender; 
        if(isMale)
            gender = "Man";
        else
            gender = "Woman";
        img = new StringItem("Player "+(i+1)+" :", gender, StringItem.BUTTON);
        img.setLayout(StringItem.LAYOUT_LEFT);
        img.setFont(fnt);
        img.setDefaultCommand(ok);
        img.setItemCommandListener(this);
        return img;
    }
    public void commandAction(Command c, Displayable d) {
        if(c == exit) ml.exit();
        else if(c == back) {
            qCh.getSelectedFlags(allowGrps);    
            ml.disp.setCurrent(this);
        }else if(c == play) {
            isSoft = isSI.isSelected(0);
            isInf = isSI.isSelected(1);
            allowGrps[0] = true; // задавать вопросы без группы
            Player p;
            TextField t;
            boolean onlM = true; // для проверки, что в игре только мужчины
            boolean onlW = true; // для проверки, что в игре только женщины
            // сохраняем имена игроков
            for(int i=ELEM; i < size(); i+=3){
                p = (Player)ml.player.elementAt((i-ELEM)/3);
                t = (TextField)get(i);
                p.name = t.getString();
                p.setMl(ml); //сохраняем ссылку на наш мидлет
                if(!p.isMale)onlM = false; //есть и женщины
                else onlW = false; //есть и мужчины
            }
            if(onlM||onlW){
                Alert a = new Alert("Error", "How are you going to play? All the players are of the same sex.", null, AlertType.ERROR);
                a.setTimeout(3000);
                ml.disp.setCurrent(a);
            }else ml.play(); // Вперёд!!!
        }
    }
    public void commandAction(Command c, Item field) {
        if(field == quest) showQSel();
        else if(field == URL)
            try {
                   ml.platformRequest("http://sexsexgame.com/?g=mob&v=0.20");
               } catch (ConnectionNotFoundException cnf){} 
        //if(c == play){ ml.play(); return;}
        // ищем какой объект вызвал ментод
        int i;
        for(i =0; i < size(); i++)
            if(get(i) == field) break;
        Player p = (Player)ml.player.elementAt((i-ELEM+1)/ 3);
        p.isMale = !p.isMale; //был мужчиной - стал женщиной
        set(i, setGender(p.isMale, (i-ELEM+1) / 3)); // стал женщиной
    }
    public void itemStateChanged(Item field) {
        if(field == qCh) // изменились выбраные вопросы
            // проверяем изменился ли флажок "Все"
            if(allowGrps[0]!=qCh.isSelected(0)){
                for(int i = 1; i < allowGrps.length; i++)
                    // устанавливаем все флажки в соответсвии с первым
                    qCh.setSelectedIndex(i, qCh.isSelected(0));
               allowGrps[0] = qCh.isSelected(0);
            }
        else if(field != n) return; // если событие не от числа игроков - выходим
        int i;
        byte N;
        try{
            N = (byte)Integer.parseInt(n.getString());
        }catch(NumberFormatException ex)
            {return;} // выходим если в n - не число
        if(N < 2){ // игроков не меньше двух
            n.setString("2");
            N = 2;
            //return;
        }
        if(N == pN) return;
        if(N > pN){
           for (i = pN; i < N; i++){
               //ml.player.addElement(new Player());
               createNameFld("Player "+(i+1), (i % 2 == 0), i);//каждый второй игрок женщина
           }
        }else{
            for (i = pN-1; i >= N; i--){
                delete(i*3+ELEM+1); // удаляем пробел
                delete(i*3+ELEM); // удаляем имя
                delete(i*3+ELEM-1); // удаляем кнопку
                ml.player.removeElementAt(i);
            }
        }
        pN = N;
    }
    private void showQSel(){ // показываем диалог выбора вопросов
         allowGrps[0] = qCh.isSelected(0);
         ml.disp.setCurrent(f);
    }
}