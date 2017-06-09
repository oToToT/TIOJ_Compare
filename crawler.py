#!/usr/bin/env python3
import requests, re, sys, json
from bs4 import BeautifulSoup

def eprint(*args, **kwargs):
    print(*args, file=sys.stderr, **kwargs)

def getResult(a):
    a=a.lower()
    req = requests.get("http://tioj.infor.org/users/"+a.lower(), allow_redirects=False)
    if req.headers['Status'] != "200 OK":
        return (False, [])
    else:
        sp = BeautifulSoup(req.text, "html.parser")
        ll = [i.text for i in sp.find("table").tbody.findAll("a",{"class":"text-success"})]
        return (True, ll)

def getPage():
    eprint("Getting Page")
    req = requests.get("http://tioj.infor.org/users", allow_redirects=False)
    sp = BeautifulSoup(req.text, "html.parser")
    return int(sp.find("div", class_="pagination").findAll("a")[-2].text)

def getNext(a):
    eprint("Getting Next")
    a=a.lower()
    for i in range(1,getPage()+1):
        eprint("Getting Page %d"%i)
        req = requests.get(f"http://tioj.infor.org/users?page={i}")
        found = BeautifulSoup(req.text, "html.parser").find("a", {"href": ("/users/"+a)})
        if found:
            eprint("Found in Page %d"%i)
            nxt = found.parent.parent.previous_sibling.previous_sibling
            if nxt:
                return nxt.find("a")['href'].split("/")[2] 
            else:
                if i==1:
                    return False
                else:
                    temp = BeautifulSoup( requests.get("http://tioj.infor.org/users?page="+str(i-1)).text, "html.parser").findAll("a", href=re.compile(r'/users/[^\?]'))
                    return temp[len(temp)-1].text
    return False
        

def Compare(a, b):
    if not a or not b:
        return (False, [])
    a, b=a.lower(), b.lower()
    eprint("Comparing %s and %s"%(a,b))
    stat, res1=getResult(a)
    if not stat:
        return (False, [])
    stat, res2=getResult(b)
    if not stat:
        return (False, [])
    return (True, {"a":[_ for _ in res1 if _ not in res2], "b":[_ for _ in res2 if _ not in res1], "ab": [_ for _ in res1 if _ in res2]})

if __name__ == "__main__":
    if len(sys.argv) == 3:
        isOK, res = Compare(sys.argv[1], sys.argv[2])
    elif len(sys.argv) == 2:
        isOK, res = Compare(sys.argv[1], getNext(sys.argv[1]))
    else:
        name1,name2 = input("Name1:"), input("Name2:")
        isOK, res = Compare(name1, name2)
    if isOK:
        res['status']=200
        print(json.dumps(res))
    else:
        print('{"status":500,"error": "error occured"}')
