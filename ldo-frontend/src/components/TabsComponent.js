// TabsComponent.js
import React, { useState } from "react";
import { Tab, Tabs, TabList, TabPanel } from "react-tabs";
import "react-tabs/style/react-tabs.css";
import TableView from "./TableView";
import GridView from "./GridView";
import { LayoutGrid, TableProperties } from "lucide-react";

function TabsComponent({ columns, data, language, isMedia }) {
    const [selectedTab, setSelectedTab] = useState(0);

    const handleTabSelect = (index) => {
        setSelectedTab(index);
    };

    return (
        <Tabs selectedIndex={selectedTab} onSelect={handleTabSelect}>
            <div
                className="flex justify-end"
                data-aos="fade-up"
                data-aos-duration="1000"
            >
                <TabList>
                    <Tab>
                        <TableProperties />
                    </Tab>
                    <Tab>
                        <LayoutGrid />
                    </Tab>
                </TabList>
            </div>

            <TabPanel>
                <TableView columns={columns} data={data} language={language} />
            </TabPanel>
            <TabPanel>
                <GridView
                    columns={columns}
                    data={data}
                    language={language}
                    isMedia={isMedia}
                />
            </TabPanel>
        </Tabs>
    );
}

export default TabsComponent;
